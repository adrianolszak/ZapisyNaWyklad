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
      .state('app.studentEdit', {
         url: '/student/:uid',
         templateUrl: 'app_edit_student.html',
         controller: 'editStudentController'
      })
      .state('app.prowadzacyEdit', {
         url: '/prowadzacy/:uid',
         templateUrl: 'app_edit_prowadzacy.html',
         controller: 'editProwadzacyController'
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
  $scope.enrolled = [];
  $scope.refresh = function(){
    $http({
      url: 'srv/RestApi.php/wyklady/',
      method: 'GET',
      transformResponse: myParseJSON
    }).success(function (response) {
       $scope.lectures = response;
    });
    $http({
      url: 'srv/RestApi.php/wyborOsoby/'+cuid.toString(),
      method: 'GET',
      transformResponse: myParseJSON
    }).success(function (response) {
      $scope.enrolled = response;
    });
  };
  $scope.enrolledTo = function(id){
    if(typeof $scope.enrolled !== 'undefined' && $scope.enrolled.length > 0){
      var r = $scope.enrolled.reduce(function(res, curr){
        return res || (curr.id_przedmiot==id);
      }, false);
      return r;
    }else{
      return false;
    }
  };
  $scope.enroll = function(uid, lid){
    var myurl = 'srv/RestApi.php/wybor/';
    $http({
      url: myurl,
      method: 'POST',
      data: {
        'id_student': cuid,
        'id_przedmiot': lid
      },
      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).success(function (response) {
       $scope.refresh();
    });
  };
  $scope.disenroll = function(uid, lid){
    var myurl = 'srv/RestApi.php/wybor/';
    $http({
      url: myurl,
      method: 'DELETE',
      data: {
        'id_student': cuid,
        'id_przedmiot': lid
      },
      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).success(function (response) {
       $scope.refresh();
    });
  };
  $scope.refresh();
})
.controller('adminLecturesController', function($scope, $http, $window) {
  $window.scrollTo(0, 0);
  $http({
    url: 'srv/RestApi.php/wyklady/',
    method: 'GET',
    transformResponse: myParseJSON
  }).success(function (response) {
     $scope.lectures = response;
  });  
  $scope.lectureDelete = function(id, index){
    var myurl = 'srv/RestApi.php/wyklad/'+id.toString();
    $http({
      url: myurl,
      method: 'DELETE'
    }).success(function (response) {
       $scope.lectures.splice(index, 1);
    });
  }
})
.controller('addLecturesController', function($scope, $http, $templateCache, $state, $window) {
  $window.scrollTo(0, 0);
  
  $http({
    url: 'srv/RestApi.php/blocks',
    method: 'GET',
    transformResponse: myParseJSON
  }).success(function (response) {
     $scope.blocks = response;
  });
  $http({
    url: 'srv/RestApi.php/kierunki',
    method: 'GET',
    transformResponse: myParseJSON
  }).success(function (response) {
     $scope.kierunki = response;
  });
  $http({
    url: 'srv/RestApi.php/prowadzacy',
    method: 'GET',
    transformResponse: myParseJSON
  }).success(function (response) {
     $scope.prowadzacy = response;
  });
  $scope.processForm = function() {
      $http({
        method: "POST",
        url: "srv/RestApi.php/wyklad",
        data: $scope.formData,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        cache: $templateCache
      }).then(function mySuccess(response) {
        //console.log(response);
        $state.go('app.users');
      }, function myError(response) {
        //console.log(response);
      });
      return false;
  };
})
.controller('usersController', function($scope, $http, $window) {
  $window.scrollTo(0, 0);
  $http({
    url: 'srv/RestApi.php/students',
    method: 'GET',
    transformResponse: myParseJSON
  }).success(function (response) {
     $scope.students = response;
  });
  $http({
    url: 'srv/RestApi.php/prowadzacy',
    method: 'GET',
    transformResponse: myParseJSON
  }).success(function (response) {
     $scope.prowadzacy = response;
  });
  $scope.studentDelete = function(uid, index){
    var myurl = 'srv/RestApi.php/students/'+uid.toString();
    $http({
      url: myurl,
      method: 'DELETE'
    }).success(function (response) {
       $scope.students.splice(index, 1);
    });
  }
  $scope.prowadzacyDelete = function(uid, index){
    var myurl = 'srv/RestApi.php/prowadzacy/'+uid.toString();
    $http({
      url: myurl,
      method: 'DELETE'
    }).success(function (response) {
       $scope.prowadzacy.splice(index, 1);
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
        $state.go('app.users');
      }, function myError(response) {
        //console.log(response);
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
    transformResponse: myParseJSON
  }).success(function (response) {
     $scope.katedry = response;
  });
  $http({
    url: 'srv/RestApi.php/tytuly',
    method: 'GET',
    transformResponse: myParseJSON
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
        $state.go('app.users');
      }, function myError(response) {
        //console.log(response);
      });
      return false;
  };
});
/*
.controller('editStudentController', function($scope, $http, $stateParams, $templateCache, $window) {
    $window.scrollTo(0, 0);
    $http({
          method: "GET",
          url: "srv/RestApi.php/student",
          data: $stateParams.uid,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          cache: $templateCache
        }).then(function mySuccess(response) {
            console.log(response);
        }, function myError(response) {
            console.log(response);
        });
})
.controller('editProwadzacyController', function($scope, $http, $stateParams, $templateCache, $window) {
    $window.scrollTo(0, 0);
    $http({
          method: "GET",
          url: "srv/RestApi.php/prowadzacy",
          data: $stateParams.uid,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          cache: $templateCache
        }).then(function mySuccess(response) {
            console.log(response);
        }, function myError(response) {
            console.log(response);
        });
});*/

function myParseJSON(data) {
  if(data)
    return data.match(/[^{}]+/g).map(function(line) {
        return JSON.parse("{"+line+"}");
    });
}