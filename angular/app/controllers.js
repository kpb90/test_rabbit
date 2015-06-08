var appControllers = angular.module('appControllers', ['ui.bootstrap']);

appControllers.controller('AppCtrl', function($scope, $parse, $http, $location) {

});

appControllers.controller('AddRIDFormCtrl', function($scope, $modal, $log, $http) {
    $scope.form = {
        'dynamicFields': [],
        'staticFields': {}
    };

    $scope.modified_form = {'dynamicFields': {'remove':[],'update':[],'add':[]},
                        'staticFields':{'update':{}}
                       }
    
/*    
    $scope.$watch(function() { return angular.toJson($scope.form);}, function(v) {
         //console.log ($scope.init_form);
         // console.log ($scope.form);
         //   console.log (difference($scope.init_form, $scope.form));
    });
*/
/*
    $scope.differenceStaticFields = function (template, override) {
        var ret = {};
        for (var name in template) {
            if (name in override) {
                if (_.isObject(override[name]) && !_.isArray(override[name])) {
                    var diff = difference(template[name], override[name]);
                    if (!_.isEmpty(diff)) {
                        ret[name] = diff;
                    }
                } else if (!_.isEqual(template[name], override[name])) {
                    ret[name] = override[name];
                }
            }
        }
        return ret;
    }
*/
    $scope.differenceDynamicFields = function (source, destination) {
        for (var i = 0; i < source.length; i++) {
            var founded = false;
            for (var j = 0; j < destination.length; j++) {
                if (source[i].id==destination[j].id) {
                    if (source[i].modified!=destination[j].modified) {
                        $scope.modified_form.dynamicFields.update.push(destination[j]);
                    }
                    destination.splice(j, 1);
                    founded = true;
                    break;
                }
            }
            if (founded == false) {
                $scope.modified_form.dynamicFields.remove.push(source[i]);
            }
            //source[i].$$hashKey
            
            source.splice(i, 1);
            i--;
        }

        for (var j = 0; j < destination.length; j++) {
            $scope.modified_form.dynamicFields.add.push(destination[j]);
        }
        source = null;
        destination = null;
    }

   $http.get('index.php?task=getRID&id=all').success(function (data,status) {
        if (data) {
            $scope.allRID = data['allRID'];
            $scope.allTemplateRID = data['allTemplateRID'];
            console.log ($scope.allRID);
        }
    }).error(function (data,status){
    });

    $scope.getConcreteRID = function (RID)  {
        $http.get('index.php?task=getRID&id='+RID).success(function (data,status) {
                if (data) {
                    $scope.form = data;
                    $scope.init_form=angular.copy($scope.form);
                }
            }).error(function (data,status){
            });
    }

    $scope.getTemplateRID = function (RID)  {
        $http.get('index.php?task=getTemplateRID&id='+RID).success(function (data,status) {
                if (data) {
                    $scope.form = data;
                }
            }).error(function (data,status){
            });
    }

    $scope.initDataForStaticFields = {
        'branches':['Судостроение','Музыка','Кино', 'Наука'],
        'security': ['все', 'зарегистрированные', 'только я'],
        'typeOfField': [{'string': 'Строковый'}, {'file': 'Файл'}, {'text': 'Текстовый'}]
    }

    $scope.initDataForDynamicFields = {
        'nameOfField': ['Вязкость', 'Вес', 'Объем'],
        'viewOfField': {
            'value': 'Значение',
            'intervalOfValues': 'Диапазон значений'
        },
        'unitsOfField': ['кг', 'см', 'шт'],
        'securityOfField': ['всем', 'зарегистрированным', 'только мне'],

    };
    $scope.animationsEnabled = true;

    $scope.addDynamicFields = function(indexTypeOfField) {
        var modalInstance = $modal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'myModalContent.html',
            controller: 'ModalInstanceCtrl',
            resolve: {
                // pass params to modal
                paramsConstruct: function() {
                    $scope.initDataForDynamicFields['typeOfField'] = indexTypeOfField;
                    return $scope.initDataForDynamicFields;
                }
            }
        });

        modalInstance.result.then(function(selectParamsConstruct) {
            //$scope.selectParamsConstruct = selectParamsConstruct;
            console.log (selectParamsConstruct);
            $scope.form.dynamicFields.push(selectParamsConstruct);
        }, function() {
            $log.info('Modal dismissed at: ' + new Date());
        });
    }


    $scope.removeDynamicField = function(index) {
      $scope.form.dynamicFields.splice (index,1);
    }

    $scope.addLinkRID = function(typeOfLink) {
        var modalInstance = $modal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'addLinkRIDContent.html',
            controller: 'ModalInstanceLinkRIDCtrl',
            resolve: {
                // pass params to modal
                params: function() {
                    return {'typeOfLink':typeOfLink, 'allRID':$scope.allRID};
                },
            }
        });

        modalInstance.result.then(function(paramsModalInstanceLink) {
            //$scope.selectParamsConstruct = selectParamsConstruct;
          //      $scope.form.staticFields.linked
        //$scope.form.staticFields.inheritable
            

        }, function() {
            $log.info('Modal dismissed at: ' + new Date());
        });

    }

});

appControllers.controller('ModalInstanceLinkRIDCtrl', function($scope, $modalInstance, params) {
    $scope.paramsModalInstanceLink = params;
    $scope.selectInstanceLink = function (selectedLink) {
        switch ($scope.paramsModalInstanceLink.typeOfLink) {
            case 'related':
               // var pos = $scope.paramsModalInstanceLink.related.indexOf(selectedLink);
               // if (pos > -1) {
                 //  $scope.paramsModalInstanceLink.related.splice(index, 1);
              //  } else {
                 //   $index 
               // }
            break;
            case 'inheritable':
                //$scope.paramsModalInstanceLink.inheritable
            break;
        }
        console.log (selectedLink);
    }

    $scope.ok = function() {
         $modalInstance.close($scope.paramsModalInstanceLink);
    };

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    };
});

appControllers.controller('ModalInstanceCtrl', function($scope, $modalInstance, paramsConstruct) {
	var keysOfParamsConstruct = Object.keys(paramsConstruct);
   
    $scope.selectParamsConstruct = {};

	for (var i = 0; i < keysOfParamsConstruct.length; i++) {
        $scope.selectParamsConstruct[keysOfParamsConstruct[i]]  = '';   
	}
    
	$scope.paramsConstruct = paramsConstruct;
	$scope.selectParamsConstruct['typeOfField'] = paramsConstruct['typeOfField'];

    $scope.ok = function() {
        if (isValid()) {
        	$modalInstance.close($scope.selectParamsConstruct);
        } else {
        	$scope.alerts = [{
                type: 'danger',
                msg: 'Все поля должны быть выбраны!'
            }];
        }
    };

    var isValid = function () {
    	switch ($scope.selectParamsConstruct['typeOfField']) {
    		case 'string':
    			return $scope.selectParamsConstruct['nameOfField'] != '' && $scope.selectParamsConstruct['viewOfField'] != ''
    		case 'text':
    			return $scope.selectParamsConstruct['nameOfField'] != ''
    		case 'file':
    			return $scope.selectParamsConstruct['nameOfField'] != ''
    	}
    	return false;
    }

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    };

    $scope.closeAlert = function(index) {
        $scope.alerts.splice(index, 1);
    };

});

appControllers.controller('RIDFormCtrl', function($scope, $http) {
    $scope.saveModelForm = function () {
                  $scope.differenceDynamicFields($scope.init_form.dynamicFields, $scope.form.dynamicFields);
                  console.log ($scope.init_form.dynamicFields);
                  console.log ($scope.form.dynamicFields);
                 console.log ($scope.modified_form.dynamicFields);
                 return;
        var fd = new FormData();
        fd.append('form', JSON.stringify($scope.form));

        if ($("input[type='file']")) {
            var files = $("input[type='file']");//.files[0];
            for (var i = 0; i < files.length; i++) {
                fd.append("uploadfile[]", files[i].files[0]);
            }
        }

        $http.post('/index.php?task=saveRID', fd, {
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

        $http.post('/index.php?task=saveTemplateRID', fd, {
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