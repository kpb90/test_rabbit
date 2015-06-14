var appAcl = angular.module('appAcl', ['ui.bootstrap'])

.controller('AclOfRIDFormCtrl', function($scope, $http, $modal, $log) {

	$scope.tuneAcl = function () {
		var modalInstance = $modal.open({
            animation: true,
            templateUrl: 'settingsAclOfRID.html',
            controller: 'ModalInstanceAclOfRIDCtrl',
            resolve: {
                params: function() {
                    return {'security':$scope.initDataForStaticFields.security,'users':$scope.form.dynamicFields['users'],'selectCommonSecurity':$scope.form.staticFields['selectCommonSecurity']};
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

.controller('ModalInstanceAclOfRIDCtrl', function($scope, $modalInstance, params) {
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

	$scope.addUserForAcl = function (email, idACL) {
		if (typeof $scope.params.users == 'undefined') {
			$scope.params.users = [];
		}
		$scope.params.users.push ({'email':email,'idACL':idACL});
		$scope.user.email = '';
		$scope.user.idACL = '';
	}
});