var appAcl = angular.module('appAcl', ['ui.bootstrap'])

.controller('AclOfRIDFormCtrl', function($scope, $http, $modal, $log) {

	$scope.tuneAcl = function () {
		var modalInstance = $modal.open({
            animation: true,
            templateUrl: 'settingsAclOfRID.html',
            controller: 'ModalInstanceAclOfRIDCtrl',
            resolve: {
                params: function() {
                    return {'idACL':$scope.initDataForStaticFields.idACL,'users':$scope.form.dynamicFields['users'],'selectCommonSecurity':$scope.form.staticFields['selectCommonSecurity']};
                },
            }
        });

        modalInstance.result.then(function(paramsAclOfRID) {
        	console.log (paramsAclOfRID);
        	$scope.form.dynamicFields['users'] = paramsAclOfRID.users;
        	$scope.form.staticFields['selectCommonSecurity'] = paramsAclOfRID.selectCommonSecurity;
        }, function() {
            $log.info('Modal dismissed at: ' + new Date());
        });
	}
})

.controller('ModalInstanceAclOfRIDCtrl', function($scope, $modalInstance, $timeout, params, helper) {
   console.log (params);
    $scope.user = {'email':'','idACL':''};
    $scope.params = JSON.parse( JSON.stringify( params) ) ;
    $scope.ok = function() {
         $modalInstance.close($scope.params);
    };

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    };

	$scope.removeUserFromAcl = function (index) {
		 $scope.params.users.splice (index,1);
	}

	$scope.addUserForAcl = function (acl_form, email, idACL, id) {
        if (acl_form.$valid) {
            if (typeof $scope.params.users == 'undefined') {
                $scope.params.users = [];
            }
            $scope.params.users.push ({'email':email,'idACL':idACL});
            
        }
        $timeout(function () {
            $scope.user.email = '';
            $scope.user.idACL = '';
        }, 500)
	}
});