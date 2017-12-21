var user = {
  id: 1,
  imie: "imie",
  nazwisko: "nazwisko",
  access: 0,
  accessName: "admin"
}

angular.module('systemApp', ['ui.router'])
.config(function($stateProvider, $urlRouterProvider){
   $stateProvider
      .state('app', {
         url: '/app',
         templateUrl: 'app.html',
         controller: 'appController'
      })
      .state('app.login', {
         url: '/login',
         templateUrl: 'login.html',
         controller: 'loginController'
      })
      .state('app.dashboard', {
         url: '/dashboard',
         templateUrl: 'app_dashboard.html',
         controller: 'appController'
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
      .state('app.myLectures', {
         url: '/myLectures',
         templateUrl: 'app_myLectures.html',
         controller: 'myLecturesController'
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
   $urlRouterProvider.otherwise('/app/login');
})
.controller('loginController', function($scope, $http, $templateCache, $state, $window) {
  $scope.processForm = function() {
      $http({
        method: "POST",
        url: "srv/RestApi.php/login",
        data: $scope.formData,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        cache: $templateCache
      }).then(function mySuccess(response) {
        if(response.data == "0"){
          window.alert("błędne dane logowania");
        }else{
          user = {
            id: response.data.id,
            imie: response.data.imie,
            nazwisko: response.data.nazwisko
          };
          $http({
            method: "GET",
            url: "srv/RestApi.php/uprawnienia/"+user.id.toString()
          }).then(function mySuccess(response) {
            user.accessName = response.data;
            switch(user.accessName) {
                case "admin":
                    user.access=0;
                    break;
                case "student":
                    user.access=2;
                    break;
                case "wykladowca":
                    user.access=1;
                    break;
                default:
                    $state.go("app.login");
                    return false;
            };
          });
          $scope.$parent.user = user;
          $state.go('app.dashboard');
        }
      }, function myError(response) {
      });
      return false;
  };
})
.controller('appController', function($scope, $http, $state, $window) {
  $scope.user = user;
  if(user.id == 1){
    $state.go("app.login");
  }
})
.controller('myLecturesController', function($scope, $http, $window) {
  $window.scrollTo(0, 0);
  $scope.refresh = function(){
    $http({
      url: 'srv/RestApi.php/wyborOsoby/'+user.id.toString(),
      method: 'GET',
      transformResponse: myParseJSON
    }).success(function (response) {
      $scope.lectures = response;
      console.log();
    });
  };
  $scope.refresh();
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
      url: 'srv/RestApi.php/wyborOsoby/'+user.id.toString(),
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
        'id_student': user.id,
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
        'id_student': user.id,
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
    url: 'srv/RestApi.php/wyklady',
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
        $state.go('app.dashboard');
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
  };
  $scope.prowadzacyDelete = function(uid, index){
    var myurl = 'srv/RestApi.php/prowadzacy/'+uid.toString();
    $http({
      url: myurl,
      method: 'DELETE'
    }).success(function (response) {
       $scope.prowadzacy.splice(index, 1);
    });
  };
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


function myParseJSON(data) {
  if(data)
    return data.match(/[^{}]+/g).map(function(line) {
        return JSON.parse("{"+line+"}");
    });
}