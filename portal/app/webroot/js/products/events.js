angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', ['mcus.filters']);
angular.module('fdb', ['fdb.services', 'fdb.directives', 'fdb.filters']).
controller('ProductEventCtrl', function ($scope, $http, $filter, $timeout) {
  $scope.currentEvent = {};
  $scope.eventItem = {
    totalItems: 0,
    addMode: false,
    copyOldItem: {},
    checkExist: false,
    isExist: false,
    showError: false,
    showDraft: true,
    popupDraft: null,
    editIndex: -1,
    showPopup: false,
    currentChanged: false,
    pages: [],
    itemsPerPage: 10,
    currentPageInc: 1,
    currentPage: 0,
    filter: '',
    userFilter: '',
    createdFromFilter: '',
    createdToFilter: '',
    listEvents: angular.copy($scope.events)
  };
  $scope.filterEventOptions = {
    textFilter: null,
    createdFromFilter: null,
    createdToFilter: null,
    statusFilter: null,
    sortField: 'created',
    sortBy: false
  };

  $scope.arrayContains = function (value, container) {
    return $.inArray(value, container) != -1;
  };

  $scope.searchMatch = function (haystack, needle) {
    if (!needle) {
      return true;
    }
    if (!haystack) {
      return false;
    }
    return haystack.toLowerCase().indexOf(needle.toLowerCase()) !== -1;
  };

  $scope.range = function (start, end) {
    var ret = [];
    if (!end) {
      end = start;
      start = 0;
    }
    for (var i = start; i < end; i++) {
      ret.push(i);
    }
    return ret;
  };

  $(document).click(function (e) {
    $timeout(function () {
      if ($('.modal-backdrop').length <= 0) {
        $scope.$apply(function () {
          $scope.eventItem.showPopup = false;
        });
      }
    }, 500);
  });

  $scope.getFromDbEventDraft = function () {
    $http({
        method: 'GET', url: Config.baseUrl + '/products/queryrac',
        params: {"type": "event"}
      }).then(function (response) {
      if (response.data && response.data.draft && response.data.draft.content) {
        $scope.eventItem.popupDraft = {};
        $scope.eventItem.popupDraft.draft = angular.fromJson(response.data.draft.content);
        $scope.eventItem.popupDraft.created = response.data.draft.created;
      } else {
        $scope.eventItem.popupDraft = null;
      }
    }, function (response) {
      throw response;
    });
  };

  $scope.initEvent = function () {
    $scope.currentEvent = {};
    $('#modal-label-add-event').text('Add New Event');
    $('#saveEvent').text('Add');
    $scope.eventItem.addMode = true;
    $scope.eventItem.copyOldItem = {};
    $scope.eventItem.checkExist = false;
    $scope.eventItem.isExist = false;
    $scope.eventItem.showError = false;
    $scope.eventItem.showDraft = true;
    $scope.eventItem.popupDraft = null;
    $scope.eventItem.editIndex = -1;
    $scope.getFromDbEventDraft();
  };

  $scope.loadDraftEvent = function () {
    if (confirm('Do you want to load this draft?')) {
      $scope.currentEvent = angular.copy($scope.eventItem.popupDraft.draft);
    }
    $scope.eventItem.showDraft = false;
  };

  $scope.checkExistsNameEvent = function () {
    $http.post(Config.baseUrl + '/products/checkExistsEvent',
            {name: $scope.currentEvent.name, id: $scope.currentEvent.id})
            .success(function (response) {
              if (response.existName == true) {
                $scope.eventItem.checkExist = true;
                $scope.eventItem.checkNotExist = false;
              } else {
                $scope.eventItem.checkNotExist = true;
                $scope.eventItem.checkExist = false;
              }
              return;
            });
  };

  $scope.$watch('currentEvent', function () {
    if ($scope.eventItem.showPopupCategory) {
      if ($scope.eventItem.copyOldItem &&
              (!angular.equals($scope.eventItem.copyOldItem, $scope.currentEvent))) {
        $scope.eventItem.currentChanged = true;
      } else {
        $scope.eventItem.currentChanged = false;
      }
    } else {
      $scope.eventItem.currentChanged = false;
    }
  }, true);

  var timeout = 10000;
  setInterval(function () {
    if ($scope.currentEvent && $scope.eventItem.addMode
      && $scope.eventItem.currentEventChanged) {
      var data = {};
      if ($scope.currentEvent && $scope.currentEvent.id) {
        data.left_id = $scope.currentEvent.id;
      }
      data.type = "event";
      data.content = angular.toJson($scope.currentEvent);
      $http.post(Config.baseUrl + '/products/addrac', data).success(
      function (response) {
        $scope.eventItem.currentChanged = false;
      });
    }
  }, timeout);

  $scope.searchEvents = function () {
    $scope.eventItem.currentPageInc = 1;
    $scope.eventItem.currentPage = 0;
    $scope.filterEventOptions.textFilter = $scope.eventItem.filter;
    $scope.filterEventOptions.userFilter = $scope.eventItem.userFilter;
    $scope.filterEventOptions.createdFromFilter = $scope.eventItem.createdFromFilter;
    $scope.filterEventOptions.createdToFilter = $scope.eventItem.createdToFilter;
    $scope.filterEventOptions.statusFilter = $scope.eventItem.statusFilter;

    if ($scope.eventItem.listEvents.length > 0) {
      if ($scope.filterEventOptions.textFilter
        || $scope.filterEventOptions.userFilter
        || $scope.filterEventOptions.createdFromFilter
        || $scope.filterEventOptions.createdToFilter
        || $scope.filterEventOptions.statusFilter) {
        var filteredItems = $filter('filter')($scope.eventItem.listEvents,
        function (item) {
          var check = false;
          if ($scope.filterEventOptions.textFilter) {
            if ($scope.searchMatch(item.event.name, $scope.filterEventOptions.textFilter)) {
              check = true;
            }
            if ($scope.searchMatch(item.event.description, $scope.filterEventOptions.textFilter)) {
              check = true;
            }
          } else {
            check = true;
          }
          var check1 = false;
          if ($scope.filterEventOptions.userFilter) {
            if ($scope.searchMatch(item.event.user_id, $scope.filterEventOptions.userFilter)) {
              check1 = true;
            }
          } else {
            check1 = true;
          }
          var check2 = false;
          if ($scope.filterEventOptions.createdFromFilter) {
            if (item.event.created) {
              var op1 = moment($scope.filterEventOptions.createdFromFilter).format('YYYY-MM-DD');
              var op2 = moment(item.event.created).format('YYYY-MM-DD');
              if (op1 <= op2) {
                check2 = true;
              }
            }
          } else {
            check2 = true;
          }
          var check3 = false;
          if ($scope.filterEventOptions.createdToFilter) {
            if (item.event.created) {
              var op1 = moment($scope.filterEventOptions.createdToFilter).format('YYYY-MM-DD');
              var op2 = moment(item.event.created).format('YYYY-MM-DD');
              if (op1 >= op2) {
                check3 = true;
              }
            }
          } else {
            check3 = true;
          }
          var check4 = false;
          if ($scope.filterEventOptions.statusFilter) {
            if ($scope.searchMatch(item.event.status, $scope.filterEventOptions.statusFilter)) {
              check4 = true;
            }
          } else {
            check4 = true;
          }
          return (check & check1 && check2 && check3 && check4);
        });
      } else {
        filteredItems = $scope.eventItem.listEvents;
      }
      var pagedItems = [];
      $scope.eventItem.totalItems = filteredItems.length;
      for (var i = 0; i < filteredItems.length; i++) {
        if (i % $scope.eventItem.itemsPerPage === 0) {
          pagedItems[Math.floor(i / $scope.eventItem.itemsPerPage)] = [filteredItems[i]];
        } else {
          pagedItems[Math.floor(i / $scope.eventItem.itemsPerPage)].push(filteredItems[i]);
        }
      }
      $scope.eventItem.pages = pagedItems;
    }
  };

  $scope.sortEvents = function () {
    if ($scope.eventItem.listEvents.length > 0) {
      if ($scope.filterEventOptions.sortBy) {
        $scope.eventItem.listEvents.sort(function (a, b) {
          var nameA = '';
          var nameB = '';
          if ($scope.filterEventOptions.sortField == 'author') {
            nameA = a.author.fullname ? a.author.fullname : '';
            nameB = b.author.fullname ? b.author.fullname : '';
          } else {
            nameA = a.event[$scope.filterEventOptions.sortField];
            nameB = b.event[$scope.filterEventOptions.sortField];
          }
          nameA = nameA ? nameA.toLowerCase() : '';
          nameB = nameB ? nameB.toLowerCase() : '';
          if (nameB > nameA) {
            return -1;
          } else if (nameB < nameA) {
            return 1;
          } else {
            return 0;
          }
        });
      } else {
        $scope.eventItem.listEvents.sort(function (a, b) {
          var nameA = '';
          var nameB = '';
          if ($scope.filterEventOptions.sortField == 'author') {
            nameA = a.author.fullname ? a.author.fullname : '';
            nameB = b.author.fullname ? b.author.fullname : '';
          } else {
            nameA = a.event[$scope.filterEventOptions.sortField];
            nameB = b.event[$scope.filterEventOptions.sortField];
          }
          nameA = nameA ? nameA.toLowerCase() : '';
          nameB = nameB ? nameB.toLowerCase() : '';
          if (nameB < nameA) {
            return -1;
          } else if (nameB > nameA) {
            return 1;
          } else {
            return 0;
          }
        });
      }
      var filteredItems = null;
      if ($scope.filterEventOptions.textFilter || $scope.filterEventOptions.userFilter
              || $scope.filterEventOptions.createdToFilter || $scope.filterEventOptions.createdFromFilter) {
        filteredItems = $filter('filter')($scope.eventItem.listEvents, function (item) {
          var check = false;
          if ($scope.filterEventOptions.textFilter) {
            if ($scope.searchMatch(item.event.name, $scope.filterEventOptions.textFilter)) {
              check = true;
            }
            if ($scope.searchMatch(item.event.status, $scope.filterEventOptions.textFilter)) {
              check = true;
            }
          } else {
            check = true;
          }
          var check1 = false;
          if ($scope.filterEventOptions.userFilter) {
            if ($scope.searchMatch(item.event.user_id, $scope.filterEventOptions.userFilter)) {
              check1 = true;
            }
          } else {
            check1 = true;
          }
          var check2 = false;
          if ($scope.filterEventOptions.createdFromFilter) {
            if (item.event.created) {
              var op1 = moment($scope.filterEventOptions.createdFromFilter).format('YYYY-MM-DD');
              var op2 = moment(item.event.created).format('YYYY-MM-DD');
              if (op1 <= op2) {
                check2 = true;
              }
            }
          } else {
            check2 = true;
          }
          var check3 = false;
          if ($scope.filterEventOptions.createdToFilter) {
            if (item.event.created) {
              var op1 = moment($scope.filterEventOptions.createdToFilter).format('YYYY-MM-DD');
              var op2 = moment(item.event.created).format('YYYY-MM-DD');
              if (op1 >= op2) {
                check3 = true;
              }
            }
          } else {
            check3 = true;
          }
          return (check & check1 && check2 && check3);
        });
      } else {
        filteredItems = $scope.eventItem.listEvents;
      }
      var pagedItems = [];
      $scope.eventItem.totalItems = filteredItems.length;
      for (var i = 0; i < filteredItems.length; i++) {
        if (i % $scope.eventItem.itemsPerPage === 0) {
          pagedItems[Math.floor(i / $scope.eventItem.itemsPerPage)] = [filteredItems[i]];
        } else {
          pagedItems[Math.floor(i / $scope.eventItem.itemsPerPage)].push(filteredItems[i]);
        }
      }
      $scope.eventItem.pages = pagedItems;
    }
  };

  $scope.sortByEvent = function (field) {
    if ($scope.filterEventOptions.sortField && $scope.filterEventOptions.sortField == field) {
      $scope.filterEventOptions.sortBy = !$scope.filterEventOptions.sortBy;
    } else {
      $scope.filterEventOptions.sortBy = true;
    }
    $scope.filterEventOptions.sortField = field;
    $scope.sortEvents();
  };

  $scope.sortEvents();

  $scope.showAllEvent = function () {
    $scope.eventItem.filter = null;
    $scope.eventItem.userFilter = null;
    $scope.eventItem.createdFromFilter = null;
    $scope.eventItem.createdToFilter = null;
    $scope.eventItem.statusFilter = null;
    $scope.searchEvents();
  };

  $scope.saveEvent = function ($status) {
    if ($scope.addEventForm.$invalid) {
      $scope.eventItem.showError = true;
      return;
    }
    $http.post(Config.baseUrl + '/products/checkExistsEvent',
      {name: $scope.currentEvent.name, id: $scope.currentEvent.id})
    .success(function (response) {
      if (response.existName == true) {
        $scope.eventItem.isExist = true;
        $scope.eventItem.checkExist = true;
        $scope.eventItem.checkNotExist = false;
        $scope.eventItem.showError = true;
        return;
      } else {
        $scope.eventItem.isExist = false;
        $scope.eventItem.checkNotExist = true;
        $scope.eventItem.checkExist = false;
        $scope.eventItem.showError = false;
        var dataSave = angular.copy($scope.currentEvent);
        if (!dataSave.id) {
          dataSave.status = 'pending';
        }
        if (dataSave.id && $status) {
          dataSave.status = $status;
        }
        $scope.eventItem.showPopup = false;
        $http.post(Config.baseUrl + '/products/saveEvent', dataSave).success(function (response) {
          if (response.status == true) {
            $http({
              method: 'GET', url: Config.baseUrl + '/products/deleterac',
              params: {"type": "event"}
            }).then(function (dataRes) {
              if ($scope.currentEvent.id) {
                $scope.eventItem.pages[$scope.eventItem.currentPage][$scope.eventItem.editIndex] = angular.copy(response.event);
                for (var i = 0; i < $scope.eventItem.listEvents.length; i++) {
                  if ($scope.eventItem.listEvents[i].event.id == response.event.event.id) {
                    $scope.eventItem.listEvents[i] = angular.copy(response.event);
                    break;
                  }
                }
              } else {
                var event = angular.copy(response.event);
                $scope.eventItem.listEvents.push(event);
                $scope.events.push(event);
                $scope.eventItem.filter = null;
                $scope.eventItem.userFilter = null;
                $scope.eventItem.createdFilter = null;
                $scope.eventItem.publishFilter = null;
                $scope.eventItem.statusFilter = null;
                $scope.filterEventOptions.textFilter = $scope.eventItem.filter;
                $scope.filterEventOptions.userFilter = $scope.eventItem.userFilter;
                $scope.filterEventOptions.createdToFilter = $scope.eventItem.createdToFilter;
                $scope.filterEventOptions.createdFromFilter = $scope.eventItem.createdFromFilter;
                $scope.filterEventOptions.statusFilter = $scope.eventItem.statusFilter;
                $scope.filterEventOptions.sortField = 'created';
                $scope.filterEventOptions.sortBy = false;
                $scope.sortEvents();
              }
              $timeout(function () {
                $('#saveEvent').trigger('reset');
              });
              $timeout(function () {
                $('#cancelEvent').click();
              });
            }, function (ex) {
              $timeout(function () {
                $('#saveEvent').trigger('reset');
              });
              $timeout(function () {
                $('#cancelEvent').click();
              });
              throw ex;
            });
          } else {
            $timeout(function () {
              $('#saveEvent').trigger('reset');
            });
            $timeout(function () {
              $('#cancelEvent').click();
            });
          }
        });
      }
    });
  };

  $scope.editEvent = function (event, indexItem) {
    $scope.eventItem.addMode = false;
    $scope.currentEvent = angular.copy(event.event);
    $scope.currentEvent.author = angular.copy(event.author);
    $scope.eventItem.copyOldItem = angular.copy($scope.currentEvent);
    $('#modal-label-add-event').text('Update Event');
    $('#saveEvent').text('Update');
    $scope.eventItem.editIndex = indexItem;
    $scope.eventItem.checkExist = false;
    $scope.eventItem.checkNotExist = false;
    $scope.eventItem.isExist = false;
    $scope.eventItem.showError = false;
    $scope.eventItem.showPopup = true;
  };

  $scope.deleteEvent = function (id) {
    $http.post(Config.baseUrl + '/products/deleteEvent/' + id)
    .success(function (response) {
      if (response.status == true) {
        for (var index = 0; index < $scope.eventItem.listEvents.length; index++) {
          if ($scope.eventItem.listEvents[index].event.id == id) {
            $scope.eventItem.listEvents.splice(index, 1);
            break;
          }
        }
        $scope.searchEvents();
      }
      $timeout(function () {
        $('#saveEvent').trigger('reset');
      });
      $timeout(function () {
        $('#cancelEvent').click();
      });
    });
  };

  $scope.setStatusEvent = function (id, index, status) {
    var data = {};
    data.id = id;
    data.status = status;
    $http.post(Config.baseUrl + '/products/saveEvent', data)
    .success(function (response) {
      if (response.status == true) {
        $scope.eventItem.pages[$scope.eventItem.currentPage][index].event.status = response.event.event.status;
        for (var s = 0; s < $scope.eventItem.listEvents.length; s++) {
          if ($scope.eventItem.listEvents[s].event.id == response.event.event.id) {
            $scope.eventItem.listEvents[s].event.status = response.event.event.status;
            break;
          }
        }
      }
    });
  };

  $scope.prevPageEvent = function () {
    if ($scope.eventItem.currentPage > 0) {
      $scope.setPageEvent($scope.eventItem.currentPage - 1);
    }
  };

  $scope.nextPageEvent = function () {
    if ($scope.eventItem.currentPage < $scope.eventItem.pages.length - 1) {
      $scope.setPageEvent($scope.eventItem.currentPage + 1);
    }
  };

  $scope.setPageEvent = function (n) {
    $scope.eventItem.currentPage = n;
    $scope.eventItem.currentPageInc = $scope.eventItem.currentPage + 1;
    $scope.eventItem.currentPageInc = $scope.eventItem.currentPageInc > $scope.eventItem.pages.length
      ? $scope.eventItem.pages.length : $scope.eventItem.currentPageInc;
    $scope.eventItem.currentPageInc = $scope.eventItem.currentPageInc < 1 ? 1 : $scope.eventItem.currentPageInc;
  };

  $scope.changePageEvent = function () {
    $scope.eventItem.currentPage = $scope.eventItem.currentPageInc - 1;
    $scope.eventItem.currentPage = $scope.eventItem.currentPage > $scope.eventItem.pages.length - 1
      ? $scope.eventItem.pages.length - 1 : $scope.eventItem.currentPage;
    $scope.eventItem.currentPage = $scope.eventItem.currentPage < 0 ? 0 : $scope.eventItem.currentPage;
    $scope.setPageEvent($scope.eventItem.currentPage);
  };

  $('#dateCategoryFilter').bind('keypress', function () {
    $('#dateCategoryFilter').val('');
    $scope.$apply(function () {
      $scope.eventItem.createdFromFilter = '';
      $scope.searchEvents();
    });
  });
  
  $('#datePublishCategoryFilter').bind('keypress', function () {
    $('#datePublishCategoryFilter').val('');
    $scope.$apply(function () {
      $scope.eventItem.createdToFilter = '';
      $scope.searchEvents();
    });
  });
});