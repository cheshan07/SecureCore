<div class="col-sm-12 col-md-8 col-lg-9">
    <div class="page-content">
        <div class="inner-box">
            <div class="dashboard-box">
                <h2 class="dashbord-title">User Roles</h2>
            </div>
            <div class="dashboard-wrapper">
                <table class="table  dashboardtable tablemyads" width="100%">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Role</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($roles != null) {
                        $html = '';
                        foreach ($roles as $row) {
                            $html .= "<tr data-category='active'>
                                        <td>$row->role_id</td>
                                        <td>$row->role</td>
                                        <td>$row->description</td>
                                      </tr>";
                        }
                    }
                    echo $html;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>