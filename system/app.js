var cuid = 1;

angular.module('systemApp', ['ui.router'])
.config(function($stateProvider, $urlRouterProvider){
   $stateProvider
      .state('app', {
         url: '/app',
         templateUrl: 'app.html'
      })
      .state('app.users', {
         url: '/users',
         templateUrl: 'app_users.html',
         controller: 'usersController'
      })
      .state('app.userEdit', {
         url: '/users/:uid',
         templateUrl: 'app_user_edit.html',
         controller: 'userEditController'
      })
      .state('app.addStudent', {
         url: '/addStudent',
         templateUrl: 'app_add_student.html',
         controller: 'addStudentController'
      })
      .state('app.addProwadzacy', {
         url: '/addProwadzacy',
         templateUrl: 'app_add_prowadzacy.html',
         controller: 'addProwadzacyController'
      })
      .state('app.lecturesEnroll', {
         url: '/enrollLectures',
         templateUrl: 'app_lectures_enroll.html',
         controller: 'enrollLecturesController'
      })
      .state('app.lecturesAdmin', {
         url: '/adminLectures',
         templateUrl: 'app_lectures_admin.html',
         controller: 'adminLecturesController'
      })
      .state('app.lecturesAdd', {
         url: '/addLectures',
         templateUrl: 'app_lectures_add.html',
         controller: 'addLecturesController'
      });
   $urlRouterProvider.otherwise('/app/users');
})
.controller('enrollLecturesController', function($scope, $http, $window) {
  $window.scrollTo(0, 0);
})
.controller('adminLecturesController', function($scope, $http, $window) {
  $window.scrollTo(0, 0);
  $http({
    url: 'srv/RestApi.php/lectures/'+cuid.toString(),
    method: 'GET',
    transformResponse: function (data) {
        return data.match(/[^{}]+/g).map(function(line) {
            return JSON.parse("{"+line+"}");
        });
    }
  }).success(function (response) {
     $scope.lectures = response;
  });
  
})
.controller('addLecturesController', function($scope, $http, $templateCache, $state, $window) {
  $window.scrollTo(0, 0);
  
  $http({
    url: 'srv/RestApi.php/blocks',
    method: 'GET',
    transformResponse: function (data) {
        return data.match(/[^{}]+/g).map(function(line) {
            return JSON.parse("{"+line+"}");
        });
    }
  }).success(function (response) {
     $scope.blocks = response;
  });
  $http({
    url: 'srv/RestApi.php/kierunki',
    method: 'GET',
    transformResponse: function (data) {
        return data.match(/[^{}]+/g).map(function(line) {
            return JSON.parse("{"+line+"}");
        });
    }
  }).success(function (response) {
     $scope.kierunki = response;
  });
  $scope.processForm = function() {
      $scope.formData.id_prowadzacy = cuid;
      $http({
        method: "POST",
        url: "srv/RestApi.php/wyklad",
        data: $scope.formData,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        cache: $templateCache
      }).then(function mySuccess(response) {
        console.log(response);
        $state.go('app.users');
      }, function myError(response) {
        console.log(response);
      });
      return false;
  };
})
.controller('usersController', function($scope, $http, $window) {
  $window.scrollTo(0, 0);
  $http({
    url: 'srv/RestApi.php/users',
    method: 'GET',
    transformResponse: function (data) {
        return data.match(/[^{}]+/g).map(function(line) {
            return JSON.parse("{"+line+"}");
        });
    }
  }).success(function (response) {
     $scope.users = response;
  });
  $scope.userDelete = function(uid, index){
    var myurl = 'srv/RestApi.php/students/'+uid.toString();
    $http({
      url: myurl,
      method: 'DELETE'
    }).success(function (response) {
       console.log(myurl);
       $scope.users.splice(index, 1);
    });
  }
})
.controller('addStudentController', function($scope, $http, $templateCache, $state, $window) {
  $window.scrollTo(0, 0);
  $window.scrollTo(0, 0);
  $http({
    url: 'srv/RestApi.php/kierunki',
    method: 'GET',
    transformResponse: function (data) {
        return data.match(/[^{}]+/g).map(function(line) {
            return JSON.parse("{"+line+"}");
        });
    }
  }).success(function (response) {
     $scope.kierunki = response;
  });
  $scope.processForm = function() {
      $http({
        method: "POST",
        url: "srv/RestApi.php/students",
        data: $scope.formData,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        cache: $templateCache
      }).then(function mySuccess(response) {
        console.log(response);
        $state.go('app.users');
      }, function myError(response) {
        console.log(response);
      });
      return false;
  };
})
.controller('addProwadzacyController', function($scope, $http, $templateCache, $state, $window) {
  $window.scrollTo(0, 0);
  $window.scrollTo(0, 0);
  $http({
    url: 'srv/RestApi.php/katedry',
    method: 'GET',
    transformResponse: function (data) {
        return data.match(/[^{}]+/g).map(function(line) {
            return JSON.parse("{"+line+"}");
        });
    }
  }).success(function (response) {
     $scope.katedry = response;
  });
  $http({
    url: 'srv/RestApi.php/tytuly',
    method: 'GET',
    transformResponse: function (data) {
        return data.match(/[^{}]+/g).map(function(line) {
            return JSON.parse("{"+line+"}");
        });
    }
  }).success(function (response) {
     $scope.title = response;
  });
  $scope.processForm = function() {
      $http({
        method: "POST",
        url: "srv/RestApi.php/prowadzacy",
        data: $scope.formData,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        cache: $templateCache
      }).then(function mySuccess(response) {
        console.log(response);
        $state.go('app.users');
      }, function myError(response) {
        console.log(response);
      });
      return false;
  };
})
.controller('userEditController', function($scope, $http, $stateParams, $templateCache, $window) {
    
    $window.scrollTo(0, 0);
    $scope.mark = {};
    $scope.can = {};
    $http({
          method: "GET",
          url: "srv/RestApi.php/users",
          data: $stateParams.surveyId,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          cache: $templateCache
        }).then(function mySuccess(response) {
            console.log(response);
        }, function myError(response) {
            console.log(response);
        });
});