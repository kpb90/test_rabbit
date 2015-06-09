var appDynamicFieldsOfread = angular.module('appDynamicFieldsOfread', ['ui.bootstrap'])

.controller('DynamicFieldsCtrl', function($scope, $http, $modal, $log) {

    $scope.addDynamicFields = function(indexTypeOfField) {
        var modalInstance = $modal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'addDynamicFields.html',
            controller: 'ModalInstanceAddDynamicFieldsCtrl',
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
})

.controller('ModalInstanceAddDynamicFieldsCtrl', function($scope, $modalInstance, paramsConstruct) {
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