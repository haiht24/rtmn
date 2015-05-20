angular.module('fdb.directives', ['mcus.directives']);
angular.module('fdb.services', ['mcus.services']);
angular.module('fdb.filters', []);
angular.module('fdb', ['fdb.services','fdb.directives', 'fdb.filters']).
controller('InboxCtrl',  function($scope , $http, $filter) {
    $(document).ready(function() {

        // Fixed table height

        tableHeightSize();

        $(window).resize(function() {
            tableHeightSize();
        });

        function tableHeightSize() {

            if ($('body').hasClass('menu-on-top')) {
                var menuHeight = 68;
                // nav height

                var tableHeight = ($(window).height() - 224) - menuHeight;
                if (tableHeight < (320 - menuHeight)) {
                    $('.table-wrap').css('height', (320 - menuHeight) + 'px');
                } else {
                    $('.table-wrap').css('height', tableHeight + 'px');
                }

            } else {
                var tableHeight = $(window).height() - 224;
                if (tableHeight < 320) {
                    $('.table-wrap').css('height', 320 + 'px');
                } else {
                    $('.table-wrap').css('height', tableHeight + 'px');
                }

            }

        }

        // LOAD INBOX MESSAGES
        loadInbox();
        function loadInbox() {
            loadURL("inbox_list", $('#inbox-content > .table-wrap'))
        }

        // Buttons (compose mail and inbox load)
        $(".inbox-load").click(function() {
            loadInbox();
        });

        // compose email
        $("#compose-mail").click(function() {
            loadURL("inbox_compose", $('#inbox-content > .table-wrap'));
        })

    });
});