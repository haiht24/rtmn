<?php
/**
 * @var $roleName - User Role
 * @var $roleColor - Color for Widget header
 * @var $roleUserList - List Users of this Role
 *
 */
?>
<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-<?= $roleColor ?>" id="wid-<?= $roleName  ?>"
     data-widget-deletebutton="false"
     data-widget-colorbutton="false"
     data-widget-editbutton="false">

    <!-- widget options:
    usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

    data-widget-colorbutton="false"
    data-widget-editbutton="false"
    data-widget-togglebutton="false"
    data-widget-deletebutton="false"
    data-widget-fullscreenbutton="false"
    data-widget-custombutton="false"
    data-widget-collapsed="true"
    data-widget-sortable="false"

    -->
    <header>
        <span class="widget-icon"> <i class="fa fa-table"></i> </span>

        <h2>User Role : <?= $roleName  ?></h2>

    </header>

    <!-- widget div-->
    <div>

        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->

        </div>
        <!-- end widget edit box -->

        <!-- widget content -->
        <div class="widget-body">

            <div class="table-responsive">

                <table id="resultTable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="width:30px">Avatar</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Skype</th>
                        <th>Department</th>
                        <th>Postal</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Replace this by roleUserList loop -->
                    <tr>
                        <td><img src="<?= $this->Html->webroot('/', true) ?>img/avatars/male.png" alt="" width="20"></td>
                        <td>Misty</td>
                        <td>mona.doreen@processproduce.edu <a href="javascript:void(0);" class="pull-right"><i class="fa fa-key"></i></a></td>
                        <td>707-118-9601</td>
                        <td>Sweetwater</td>
                        <td>Lorem dolor sit amet</td>
                        <td>94133</td>
                        <td><span class="label label-success">Active</span></td>
                    </tr>
                    <tr class="danger">
                        <td><img src="<?= $this->Html->webroot('/', true) ?>img/avatars/male.png" alt="" width="20"></td>
                        <td>Cleo</td>
                        <td>collin@berry.info <a href="javascript:void(0);" class="pull-right"><i class="fa fa-key"></i></a></td>
                        <td>543-827-8732</td>
                        <td>Groesbeck</td>
                        <td>Lorem dolor sit amet</td>
                        <td>12764</td>
                        <td><span class="label label-danger">Disabled</span></td>
                    </tr>
                    <tr>
                        <td><img src="<?= $this->Html->webroot('/', true) ?>img/avatars/male.png" alt="" width="20"></td>
                        <td>Eliza</td>
                        <td>lawanda@event.me <a href="javascript:void(0);" class="pull-right"><i class="fa fa-key"></i></a></td>
                        <td>453-985-9884</td>
                        <td>Alto</td>
                        <td>Lorem dolor sit amet</td>
                        <td>70454</td>
                        <td><span class="label label-success">Active</span></td>
                    </tr>
                    <tr class="success">
                        <td><img src="<?= $this->Html->webroot('/', true) ?>img/avatars/male.png" alt="" width="20"></td>
                        <td>Chantel</td>
                        <td>marilynn.lucretia@animalanswer.edu <a href="javascript:void(0);" class="pull-right"><i class="fa fa-key"></i></a></td>
                        <td>789-917-1518</td>
                        <td>Lozano</td>
                        <td>Lorem dolor sit amet</td>
                        <td>46151</td>
                        <td><span class="label label-primary">ADMIN</span></td>
                    </tr>
                    <tr>
                        <td><img src="<?= $this->Html->webroot('/', true) ?>img/avatars/male.png" alt="" width="20"></td>
                        <td>Tisha</td>
                        <td>luella@square.me <a href="javascript:void(0);" class="pull-right"><i class="fa fa-key"></i></a></td>
                        <td>510-644-1193</td>
                        <td>Dayton</td>
                        <td>Lorem dolor sit amet</td>
                        <td>18943</td>
                        <td><span class="label label-success">Active</span></td>
                    </tr>
                    <tr>
                        <td><img src="<?= $this->Html->webroot('/', true) ?>img/avatars/female.png" alt="" width="20"></td>
                        <td>Rebekah</td>
                        <td>janelle.lourdes.laurel@antany.edu <a href="javascript:void(0);" class="pull-right"><i class="fa fa-key"></i></a></td>
                        <td>345-807-9800</td>
                        <td>Laureles</td>
                        <td>Lorem dolor sit amet</td>
                        <td>26524</td>
                        <td><span class="label label-success">Active</span></td>
                    </tr>
                    <tr class="warning">
                        <td><img src="<?= $this->Html->webroot('/', true) ?>img/avatars/male.png" alt="" width="20"></td>
                        <td>Lesley</td>
                        <td>pam.kelli@recordred.me <a href="javascript:void(0);" class="pull-right"><i class="fa fa-key"></i></a></td>
                        <td>255-974-8448</td>
                        <td>Eagle Lake</td>
                        <td>Lorem dolor sit amet</td>
                        <td>83430</td>
                        <td><span class="label label-warning">Inactive</span></td>
                    </tr>
                    <tr>
                        <td><img src="<?= $this->Html->webroot('/', true) ?>img/avatars/male.png" alt="" width="20"></td>
                        <td>Josephine</td>
                        <td>magdalena@accountacid.me <a href="javascript:void(0);" class="pull-right"><i class="fa fa-key"></i></a></td>
                        <td>502-841-8206</td>
                        <td>Stagecoach</td>
                        <td>89756</td>
                        <td>Lorem dolor sit amet</td>
                        <td><span class="label label-success">Active</span></td>
                    </tr>

                    </tbody>
                </table>

            </div>

            <div class="text-center">
                <hr>
                <ul class="pagination no-margin">
                    <li class="prev disabled">
                        <a href="javascript:void(0);">Previous</a>
                    </li>
                    <li class="active">
                        <a href="javascript:void(0);">1</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">2</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">3</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">4</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">5</a>
                    </li>
                    <li class="next">
                        <a href="javascript:void(0);">Next</a>
                    </li>
                </ul>
            </div>

        </div>
        <!-- end widget content -->

    </div>
    <!-- end widget div -->

</div>
<!-- end widget -->