var appControllers = angular.module('appControllers', ['ui.bootstrap']);

appControllers.controller('AppCtrl', function($scope, $parse, $http, $location) {

});

appControllers.controller('AddRIDFormCtrl', function($scope, $modal, $log, $http) {
    $scope.form = {
        'dynamicFields': [],
        'staticFields': {}
    };

   $http.get('/index.php?task=getRID&id=all').success(function (data,status) {
        if (data) {
            $scope.allRID = data['allRID'];
            $scope.allTemplateRID = data['allTemplateRID'];
            console.log ($scope.allRID);
        }
    }).error(function (data,status){
    });

    $scope.getConcreteRID = function (RID)  {
        $http.get('/index.php?task=getRID&id='+RID).success(function (data,status) {
                if (data) {
                    $scope.form = data;
                }
            }).error(function (data,status){
            });
    }

    $scope.getTemplateRID = function (RID)  {
        $http.get('/index.php?task=getTemplateRID&id='+RID).success(function (data,status) {
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