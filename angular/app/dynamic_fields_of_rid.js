var appDynamicFieldsOfread = angular.module('appDynamicFieldsOfread', ['ui.bootstrap'])

.controller('DynamicFieldsCtrl', function($scope, $http, $modal, $log) {

    $scope.addDynamicFields = function(selectTypeOfField) {
        var modalInstance = $modal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'addDynamicFields.html',
            controller: 'ModalInstanceAddDynamicFieldsCtrl',
            resolve: {
                // pass params to modal
                paramsConstruct: function() {
                    $scope.initDataForDynamicFields['selectTypeOfField'] = selectTypeOfField;
                    return $scope.initDataForDynamicFields;
                }
            }
        });

        modalInstance.result.then(function(selectParamsConstruct) {
            //$scope.selectParamsConstruct = selectParamsConstruct;
            console.log (selectParamsConstruct);
            $scope.form.dynamicFields['addField'].push(selectParamsConstruct);
        }, function() {
            $log.info('Modal dismissed at: ' + new Date());
        });
    }

    $scope.removeDynamicField = function(index) {
      $scope.form.dynamicFields['addField'].splice (index,1);
    }
})

.controller('ModalInstanceAddDynamicFieldsCtrl', function($scope, $modalInstance, paramsConstruct, helper) {
	var keysOfParamsConstruct = Object.keys(paramsConstruct);
   
    $scope.selectParamsConstruct = {};

	for (var i = 0; i < keysOfParamsConstruct.length; i++) {
        $scope.selectParamsConstruct[keysOfParamsConstruct[i]]  = '';   
	}

	$scope.paramsConstruct = paramsConstruct;
	$scope.selectParamsConstruct['selectTypeOfField'] = paramsConstruct['selectTypeOfField'];

    $scope.ok = function(modal_dynamic_fields) {
        if (modal_dynamic_fields.$valid) {
            if (angular.isObject($scope.selectParamsConstruct ['nameOfField'])===false) {
                $scope.selectParamsConstruct ['nameOfField'] = {'title':$scope.selectParamsConstruct ['nameOfField'], 'new_record':true};
            }

            if (typeof $scope.selectParamsConstruct ['unitsOfField'] != 'undefined') {
                if (angular.isObject($scope.selectParamsConstruct ['unitsOfField'])===false) {
                    $scope.selectParamsConstruct ['unitsOfField'] = {'tfru_title':$scope.selectParamsConstruct ['nameOfField']['title'], 'u_title':$scope.selectParamsConstruct ['unitsOfField'], 'new_record':true};
                }
            }
            // viewOfField = 2 - 2 editbox
            // viewOfField = 1 - 1 editbox
            // viewOfField = "" - вид уже определен в template
            if ($scope.selectParamsConstruct ['viewOfField']==2) {
            $scope.selectParamsConstruct ['value'] = [{'value':""}, {'value':""}];
            } else {
                $scope.selectParamsConstruct ['value'] = [{'value':""}];
            }
            $modalInstance.close($scope.selectParamsConstruct);
        } else {
        	$scope.alerts = [{
                type: 'danger',
                msg: 'Форма заполнена неверно'
            }];
        }
    };

    $scope.cancel = function() {
        console.log ($scope.selectParamsConstruct);
        $modalInstance.dismiss('cancel');
    };

    $scope.closeAlert = function(index) {
        $scope.alerts.splice(index, 1);
    };

});