var appControllers = angular.module('appControllers', ['ui.bootstrap']);


appControllers.controller('AppCtrl', function($scope, $parse, $http, $location) {

});

appControllers.controller('AddRIDFormCtrl', function($scope, $modal, $log, $http) {

    (function () {
       $http.get('/index.php?module=addRID&task=getInitDataForRID').success(function (data,status) {
            $scope.initDataForStaticFields = data['initDataForStaticFields'];
            data['initDataForDynamicFields']['nameOfField'] = objToArray(data['initDataForDynamicFields']['nameOfField']);
            $scope.initDataForDynamicFields = data['initDataForDynamicFields'];
            $scope.initDataForDynamicFields['security'] = $scope.initDataForStaticFields['security'];
            $scope.allRID = data['allRID'];
            console.log (data);
        }).error(function (data,status){
        });

        $http.get('index.php?module=addRID&task=getRID&id=all').success(function (data,status) {
            if (data) {
                $scope.allTemplateRID = data['allTemplateRID'];
               // console.log ($scope.allRID);
            }
        }).error(function (data,status){
        });

    })();
    function objToArray(myObj) {
        var arr = [];
        for( var i in myObj ) {
            if (myObj.hasOwnProperty(i)){
                    arr[i] = myObj[i];
            }
        }
        return arr;
    }
    $scope.form = {
        'dynamicFields': {'addField':[],'users':[],'selectionRelated':[],'selectionInheritable':[]},
        'staticFields': {}
    };
    
    $scope.init_form = angular.copy($scope.form);

    $scope.initModifiedForm = function  () {
        return {
                'addField':{'remove':[],'update':[],'add':[]},
                'users':{'remove':[],'update':[],'add':[]},
                'selectionRelated':{'remove':[],'update':[],'add':[]},
                'selectionInheritable':{'remove':[],'update':[],'add':[]},
            };
    }
  
/*
    $scope.$watch(function() { return angular.toJson($scope.form);}, function(new_val, old_val) {
        console.log (new_val);
        console.log (old_val);
        debugger;
         //console.log ($scope.init_form);
         // console.log ($scope.form);
         //   console.log (difference($scope.init_form, $scope.form));
    });
*/
    function compareObj (obj1,obj2) {
        if (obj1.hasOwnProperty('value') && obj2.hasOwnProperty('value')) {
            return obj1['value']==obj2['value'];
        }
    }

    $scope.differenceDynamicFields = function (source, destination) {
        $scope.modifiedForm = $scope.initModifiedForm();
        source = JSON.parse(JSON.stringify(source));
        destination = JSON.parse(JSON.stringify(destination));

        for (var key in $scope.modifiedForm) {
            for (var i in source[key]) {
                var founded = false;
                for (var j in destination[key]) {
                    if (source[key][i].id==destination[key][j].id) {
                        if (compareObj(source[key][i],destination[key][j])===false) {
                            $scope.modifiedForm[key].update.push(destination[key][j]);
                        }
                        delete destination[key][j];
                        founded = true;
                        break;
                    }
                }
                if (founded == false) {
                    $scope.modifiedForm[key].remove.push(source[key][i]);
                }
                delete source[key][i];
                i--;
            }

            for (var j = 0; j < destination[key].length; j++) {
                $scope.modifiedForm[key].add.push(destination[key][j]);
            }
        }
    }

    $scope.getConcreteRID = function (RID)  {
        $http.get('index.php?module=addRID&task=getRID&id='+RID).success(function (data,status) {
                if (data) {
                    $scope.form = data;
                    $scope.form.dynamicFields.addField = objToArray($scope.form.dynamicFields.addField);
                    $scope.init_form=angular.copy($scope.form);
                    console.log ($scope.form);
                }
            }).error(function (data,status){
            });
    }

    $scope.getTemplateRID = function (RID)  {
        $http.get('index.php?module=addRID&task=getTemplateRID&id='+RID).success(function (data,status) {
                if (data) {
                    $scope.form = data;
                }
            }).error(function (data,status){
            });
    }
});

appControllers.controller('RIDFormCtrl', function($scope, $http) {
    $scope.saveModelForm = function () {
         $scope.differenceDynamicFields($scope.init_form.dynamicFields, $scope.form.dynamicFields);
                 // console.log ($scope.init_form.dynamicFields);
        var fd = new FormData();
        fd.append('form', JSON.stringify($scope.form));
        fd.append('modifiedForm', JSON.stringify($scope.modifiedForm));

        if ($("input[type='file']")) {
            var files = $("input[type='file']");//.files[0];
            for (var i = 0; i < files.length; i++) {
                fd.append("uploadfile[]", files[i].files[0]);
            }
        }

        $http.post('index.php?module=addRID&task=saveRID', fd, {
                     withCredentials: true,
                     headers: {'Content-Type': undefined },
                     transformRequest: angular.identity
        }).success(function (data,status) {
            console.log (data);
            $scope.init_form = angular.copy($scope.form);
            $scope.initModifiedForm ();
        }).error(function (data,status){
            console.log (data);
            console.log ('Ошибка соедениения с сервером при обновлении');
        });
    }

    $scope.saveTemplateForm = function () {
        var fd = new FormData();
        var copyForm = JSON.parse( JSON.stringify( $scope.form) ) ;
        
        for (var i in copyForm['staticFields']) {
            copyForm['staticFields'][i] = '';
        }

        for (i = 0; i < copyForm['dynamicFields'].length; i ++) {
            if (typeof copyForm['dynamicFields'][i]['value'] != 'undefined') {
                copyForm['dynamicFields'][i]['value'] = '';
            } else {
                if (typeof copyForm['dynamicFields'][i]['value1'] != 'undefined') {
                    copyForm['dynamicFields'][i]['value1'] = '';
                }
                if (typeof copyForm['dynamicFields'][i]['value2'] != 'undefined') {
                    copyForm['dynamicFields'][i]['value2'] = '';
                }
            }
        }

        fd.append('form', JSON.stringify(copyForm));

        $http.post('index.php?module=addRID&task=saveTemplateRID', fd, {
                     withCredentials: true,
                     headers: {'Content-Type': undefined },
                     transformRequest: angular.identity
        }).success(function (data,status) {
            console.log (data);
        }).error(function (data,status){
            console.log (data);
            console.log ('Ошибка соедениения с сервером при обновлении');
        });

    }
});