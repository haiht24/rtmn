angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', ['mcus.filters']);
angular.module('fdb', ['fdb.services','fdb.directives', 'fdb.filters']).
controller('ProductCateCtrl',  function($scope , $http, $filter, $timeout) {
    $scope.newCategory = {};
    $scope.pages = [];
    $scope.itemsPerPage = 20;
    $scope.currentPageInc = 1;
    $scope.currentPage = 0;
    $scope.filter = '';
    $scope.checkExist = false;
    $scope.checkNotExist = false;
    $scope.isExist = false;
    $scope.userFilter = '';
    $scope.createdFilter = '';
    $scope.publishFilter = '';
    $scope.showPopupCategory = false;
    $scope.editItem = -1;
    $scope.copyOldItem = null;
    $scope.showDraft = true;
    $scope.currentCategoryChanged = false;
    $scope.popupDraft = null;
    $scope.addMode = false;
    
    $scope.filterOptions = {
        textFilter: null,
        publishDateFilter: null,
        sortField: null,
        sortBy: null
    };
    
    $(document).click(function(e) {
        $timeout(function() {
            if ($('.modal-backdrop').length <= 0) {
                $scope.$apply(function(){
                    $scope.showPopupCategory = false;
                }); 
           }
        }, 500);
    });
    
    if ($scope.listCategories.length > 0) {
        var pagedItems = [];
        for (var i = 0; i < $scope.listCategories.length; i++) {
            if (i % $scope.itemsPerPage === 0) {
                pagedItems[Math.floor(i / $scope.itemsPerPage)] = [$scope.listCategories[i]];
            } else {
                pagedItems[Math.floor(i / $scope.itemsPerPage)].push($scope.listCategories[i]);
            }
        }
        $scope.pages = pagedItems;
    }
    
    $scope.checkExists = function() {
        $http.post(Config.baseUrl + '/products/checkExistsCate',
                {name: $scope.newCategory.name, id: $scope.newCategory.id})
            .success(function(response) {
                if (response.existName == true) {
                    $scope.checkExist = true;
                    $scope.checkNotExist = false;
                } else {
                    $scope.checkNotExist = true;
                    $scope.checkExist = false;
                }
                return;
        });
    };
    
    $scope.searchMatch = function(haystack, needle) {
        if (!needle) {
            return true;
        }
        if (!haystack) {
            return false;
        }
        return haystack.toLowerCase().indexOf(needle.toLowerCase()) !== -1;
    };
    
    $scope.showAll = function() {
        $scope.filter = null;
        $scope.search();
    };
    
    $scope.search = function() {
        $scope.currentPageInc = 1;
        $scope.currentPage = 0;
        $scope.filterOptions.textFilter = $scope.filter;
        $scope.filterOptions.userFilter = $scope.userFilter;
        $scope.filterOptions.createdFilter = $scope.createdFilter;
        $scope.filterOptions.publishDateFilter = $scope.publishFilter;
        if ($scope.listCategories.length > 0) {
            if ($scope.filterOptions.textFilter || $scope.filterOptions.userFilter
                    || $scope.filterOptions.createdFilter || $scope.filterOptions.publishDateFilter) {
                var filteredItems = $filter('filter')($scope.listCategories, function(item) {
                    var check = false;
                    if ($scope.filterOptions.textFilter) {
                        if ($scope.searchMatch(item.category.name, $scope.filter)) {
                            check = true;
                        }
                        if ($scope.searchMatch(item.category.alias, $scope.filter)) {
                            check = true;
                        }
                        if ($scope.searchMatch(item.category.status, $scope.filter)) {
                            check = true;
                        }
                    } else {
                        check = true;
                    }
                    var check1 = false;
                    if ($scope.filterOptions.userFilter) {
                        if ($scope.searchMatch(item.category.user_id, $scope.filterOptions.userFilter)) {
                           check1 = true;
                        }
                    } else {
                        check1 = true;
                    }
                    var check2 = false;
                    if ($scope.filterOptions.createdFilter) {
                        if ($scope.searchMatch(item.category.created, $scope.filterOptions.createdFilter)) {
                           check2 = true;
                        }
                    } else {
                        check2 = true;
                    }
                    var check3 = false;
                    if ($scope.filterOptions.publishDateFilter) {
                        if ($scope.searchMatch(item.category.publish_date, $scope.filterOptions.publishDateFilter)
                            && $scope.searchMatch(item.category.status, 'published')) {
                           check3 = true;
                        }
                    } else {
                        check3 = true;
                    }
                    return (check & check1 && check2 && check3);
                });
            } else {
                filteredItems = $scope.listCategories;
            }
            var pagedItems = [];
            for (var i = 0; i < filteredItems.length; i++) {
                if (i % $scope.itemsPerPage === 0) {
                    pagedItems[Math.floor(i / $scope.itemsPerPage)] = [filteredItems[i]];
                } else {
                    pagedItems[Math.floor(i / $scope.itemsPerPage)].push(filteredItems[i]);
                }
            }
            $scope.pages = pagedItems;
        }
    };

    $scope.sortBy = function(field) {
        if($scope.filterOptions.sortField && $scope.filterOptions.sortField == field) {
            $scope.filterOptions.sortBy = !$scope.filterOptions.sortBy;
        } else {
            $scope.filterOptions.sortBy = true;
        }
        $scope.filterOptions.sortField = field;

        if ($scope.listCategories.length > 0) {
            if ($scope.filterOptions.sortBy) {
                $scope.listCategories.sort(function(a, b) {
                    var nameA  = '';
                    var nameB  = '';
                    if (field == 'father') {
                        nameA  = a.father.name;
                        nameB  = b.father.name;
                    } else if (field == 'author') {
                        nameA  = a.author.fullname ? a.author.fullname : '';
                        nameB  = b.author.fullname ? b.author.fullname : '';
                    } else {
                        nameA  = a.category[field];
                        nameB  = b.category[field];
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
                $scope.listCategories.sort(function(a, b) {
                    var nameA  = '';
                    var nameB  = '';
                    if (field == 'father') {
                        nameA  = a.father.name;
                        nameB  = b.father.name;
                    } else if (field == 'author') {
                        nameA  = a.author.fullname ? a.author.fullname : '';
                        nameB  = b.author.fullname ? b.author.fullname : '';
                    } else {
                        nameA  = a.category[field];
                        nameB  = b.category[field];
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
            if ($scope.filterOptions.textFilter || $scope.filterOptions.userFilter
                    || $scope.filterOptions.createdFilter || $scope.filterOptions.publishDateFilter) {
                filteredItems = $filter('filter')($scope.listCategories, function(item) {
                    var check = false;
                    if ($scope.filterOptions.textFilter) {
                        if ($scope.searchMatch(item.category.name, $scope.filterOptions.textFilter)) {
                            check = true;
                        }
                        if ($scope.searchMatch(item.category.alias, $scope.filterOptions.textFilter)) {
                            check = true;
                        }
                        if ($scope.searchMatch(item.category.status, $scope.filterOptions.textFilter)) {
                            check = true;
                        }
                    } else {
                        check = true;
                    }
                    var check1 = false;
                    if ($scope.filterOptions.userFilter) {
                        if ($scope.searchMatch(item.category.user_id, $scope.filterOptions.userFilter)) {
                           check1 = true;
                        }
                    } else {
                        check1 = true;
                    }
                    var check2 = false;
                    if ($scope.filterOptions.createdFilter) {
                        if ($scope.searchMatch(item.category.created, $scope.filterOptions.createdFilter)) {
                           check2 = true;
                        }
                    } else {
                        check2 = true;
                    }
                    var check3 = false;
                    if ($scope.filterOptions.publishDateFilter) {
                        if ($scope.searchMatch(item.category.publish_date, $scope.filterOptions.publishDateFilter)
                            && $scope.searchMatch(item.category.status, 'published')) {
                           check3 = true;
                        }
                    } else {
                        check3 = true;
                    }
                    return (check & check1 && check2 && check3);
                });
            } else {
                filteredItems = $scope.listCategories;
            }
            var pagedItems = [];
            for (var i = 0; i < filteredItems.length; i++) {
                if (i % $scope.itemsPerPage === 0) {
                    pagedItems[Math.floor(i / $scope.itemsPerPage)] = [filteredItems[i]];
                } else {
                    pagedItems[Math.floor(i / $scope.itemsPerPage)].push(filteredItems[i]);
                }
            }
            $scope.pages = pagedItems;
        }
    };
    
    $scope.range = function(start, end) {
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

    $scope.prevPage = function() {
        if ($scope.currentPage > 0) {
            $scope.setPage($scope.currentPage - 1);
        }
    };

    $scope.nextPage = function() {
        if ($scope.currentPage < $scope.pages.length - 1) {
            $scope.setPage($scope.currentPage + 1);
        }
    };

    $scope.setPage = function(n) {
        $scope.currentPage = n;
        $scope.currentPageInc = $scope.currentPage + 1;
        $scope.currentPageInc = $scope.currentPageInc > $scope.pages.length ? $scope.pages.length : $scope.currentPageInc;
        $scope.currentPageInc = $scope.currentPageInc < 1 ? 1 : $scope.currentPageInc;
    };

    $scope.changePage = function() {
        $scope.currentPage = $scope.currentPageInc - 1;
        $scope.currentPage = $scope.currentPage > $scope.pages.length - 1 ? $scope.pages.length - 1 : $scope.currentPage;
        $scope.currentPage = $scope.currentPage < 0 ? 0 : $scope.currentPage;
        $scope.setPage($scope.currentPage);
    };

    $scope.initCategory = function() {
        $scope.showPopupCategory = true;
        $scope.addMode = true;
        $scope.newCategory = {};
        $scope.copyOldItem = {};
        $('#modal-label-add-cate').text('Add New Category');
        $('#saveCate').text('Add');
        $scope.checkExist = false;
        $scope.checkNotExist = false;
        $scope.isExist = false;
        $scope.showError = false;
        $scope.getFromDbCategoryDraft();
        $scope.showDraft = true;
    };

    $scope.editCategory = function(category, indexItem) {
        $scope.addMode = false;
        $scope.newCategory = angular.copy(category);
        $scope.copyOldItem = angular.copy(category);
        $('#modal-label-add-cate').text('Update Category');
        $('#saveCate').text('Update');
        $scope.checkExist = false;
        $scope.checkNotExist = false;
        $scope.isExist = false;
        $scope.showError = false;
        $scope.editItem = indexItem;
        $scope.showPopupCategory = true;
    };
    
    $scope.deleteCategory = function(id ) {
        $http.post(Config.baseUrl + '/products/deleteCategory/' + id).success(function(response) {
            if (response.status == true) {
                for(var i=0; i < $scope.listCategories.length; i++) {
                    if($scope.listCategories[i].category.id == id) {
                        $scope.listCategories.splice(i,1);
                        break;
                    }
                }
                $scope.search();
            }
        });
    };
    
    $scope.setStatusCategory = function(id, index, status) {
        var data = {};
        data.id = id;
        data.status = status;
        $http.post(Config.baseUrl + '/products/saveCategory', data).success(function(response) {
            if (response.status == true) {
                $scope.pages[$scope.currentPage][index].category.status = response.category.category.status;;
                for(var i=0; i < $scope.listCategories.length; i++) {
                    if($scope.listCategories[i].category.id == response.category.category.id) {
                        $scope.listCategories[i].category.status = response.category.category.status;
                        break;
                    }
                }
            }
        });
    };
    
    $scope.saveCategory = function($status) {
        if ($scope.addCateForm.$invalid) {
          $scope.showError = true;
          return;
        }
        if ($scope.newCategory.id) {
            $scope.newCategory.status = $status;
        }
        $http.post(Config.baseUrl + '/products/checkExistsCate',
                {name: $scope.newCategory.name, id: $scope.newCategory.id})
        .success(function(response) {
            if (response.existName == true) {
                $scope.isExist = true;
                $scope.checkExist = true;
                $scope.checkNotExist = false;
                $scope.showError = true;
                return;
            } else {
                $scope.isExist = false;
                $scope.checkNotExist = true;
                $scope.checkExist = false;
                $scope.showError = false;
                if (!$scope.newCategory.id) {
                    $scope.newCategory.status = 'pending';
                }
                $scope.showPopupCategory = false;
                $http.post(Config.baseUrl + '/products/saveCategory', $scope.newCategory).success(function(response) {
                    if (response.status == true) {
                        $http({method: 'GET', url: Config.baseUrl + '/products/deleterac', params: {"type": "category"}}).then(function(dataRes) {
                            if ($scope.newCategory.id) {
                                $scope.pages[$scope.currentPage][$scope.editItem] = angular.copy(response.category);
                                for(var i=0; i < $scope.listCategories.length; i++) {
                                    if($scope.listCategories[i].category.id == response.category.category.id) {
                                        $scope.listCategories[i] = angular.copy(response.category);
                                        break;
                                    }
                                }
                            } else {
                                var cate = angular.copy(response.category);
                                $scope.listCategories.push(cate);
                                if(!cate.category.parent_id) {
                                    $scope.categories.push(cate);
                                } else {
                                  for(var i=0; i < $scope.categories.length; i++) {
                                      if(cate.category.parent_id == $scope.categories[i].category.id) {
                                          if(!$scope.categories[i].category.sub_category) {
                                              $scope.categories[i].category.sub_category = [];
                                          }
                                          $scope.categories[i].category.sub_category.push(cate);
                                          break;
                                      }
                                  }
                                }
                                if (($scope.listCategories.length -1) % $scope.itemsPerPage === 0) {
                                    $scope.pages[Math.floor(($scope.listCategories.length - 1) / $scope.itemsPerPage)] = [cate];
                                } else {
                                    $scope.pages[Math.floor(($scope.listCategories.length - 1)  / $scope.itemsPerPage)].push(cate);
                                }
                            }
                            $scope.newCategory = {};
                            $scope.imgLoading = false;
                            $timeout(function () {
                                $('#saveCate').trigger('reset');
                            });
                            $timeout(function () {
                                $('#cancelCate').click();
                            });
                        }, function(ex) {
                            $scope.imgLoading = false;
                            $timeout(function () {
                                $('#saveCate').trigger('reset');
                            });
                            $timeout(function () {
                                $('#cancelCate').click();
                            });
                            throw ex;
                        });
                    } else {
                        $scope.imgLoading = false;
                        $timeout(function () {
                            $('#saveCate').trigger('reset');
                        });
                        $timeout(function () {
                            $('#cancelCate').click();
                        });
                    }
                    
                });
            }
        });
    };
    
    $scope.arrayContains = function(value, container) {
        return $.inArray(value, container) != -1;
    };
    
    $('#dateFilter').bind('keypress', function(e) {
        $('#dateFilter').val('');
        $scope.$apply(function() {
            $scope.createdFilter = '';
            $scope.search();
        });
    });
    $('#datePublishFilter').bind('keypress', function(e) {
        $('#datePublishFilter').val('');
        $scope.$apply(function() {
            $scope.publishFilter = '';
            $scope.search();
        });
    });
    $scope.$watch('newCategory', function() {
        if ($scope.showPopupCategory) {
            if ($scope.copyOldItem && (!angular.equals($scope.copyOldItem, $scope.newCategory))) {
                $scope.currentCategoryChanged = true;
            } else {
                $scope.currentCategoryChanged = false;
            }
        } else {
            $scope.currentCategoryChanged = false;
        }
    }, true);
    
    // if user is logged in save survey as draft every 10 sec
    var timeout = 10000;
    setInterval(function(){
        if ($scope.newCategory && $scope.currentCategoryChanged) {
            var data = {};
            if ($scope.newCategory && $scope.newCategory.id) {
                data.left_id = $scope.newCategory.id;
            }
            data.type = "category";
            data.content = angular.toJson($scope.newCategory);
            $http.post(Config.baseUrl + '/products/addrac', data).success(function(response) {
                $scope.currentCategoryChanged = false;
            });
        }
    }, timeout);
    
    $scope.getFromDbCategoryDraft = function() {
        //load drafts
        $http({method: 'GET', url: Config.baseUrl + '/products/queryrac', params: {"type": "category"}}).then(function(response) {
            if (response.data && response.data.draft && response.data.draft.content) {
                $scope.popupDraft = {};
                $scope.popupDraft.draft = angular.fromJson(response.data.draft.content);
                $scope.popupDraft.created = response.data.draft.created;
            } else {
                $scope.popupDraft = null;
            }
        }, function(response) {
            throw response;
        });
    };
    
    $scope.getFromDbCategoryDraft();
    // load a draft and overwrites the current survey
    $scope.loadDraft = function() {
        if (confirm('Do you want to load this draft?')) {
            $scope.newCategory = angular.copy($scope.popupDraft.draft);
        }
        $scope.showDraft = false;
    };
});