<div class="col-sm-5">

    <form class="form-horizontal form-new-fee inputform"  data-parsley-validate>
    
        <div class="panel panel-default text-center login-form-bg" data-z="0.5">
            
            <h3 class="text-display-1 text-center margin-bottom-none">Select Period</h3>
            <hr>
            <div class="panel-body">
                
                    <div class="form-group">
                    
                        <input type="hidden" name="student_id" value="<?=$student_id?>">
                        <input type="hidden" name="sch_id" value="<?=$sch_id?>">
                        <input type="hidden" name="reg_no" value="<?=$reg_no?>">
                        <input type="hidden" name="class" value="<?=$current_class?>">
                        
                        <label for="year" class="col-sm-3 control-label">Year</label>
                        <div class="col-sm-9">
                            <select id="fee_year" name="fee_year" class="form-control selectpickerz text-center" data-parsley-trigger="change" required>
                                    
                                <?php
                                        
                                    $years = array();
                                    $years = $db->getYearData();
                                    
                                    foreach ($years as $i => $row)
                                    {
                                        echo "id - " . $row['id'];
                                        echo "name - " .$row['name'];
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                
                                ?>
                                
                            </select>
                        </div>
                        
                    </div>
                
            </div>
            
        </div>
        
        <div class="panel panel-default text-center login-form-bg" data-z="0.5">
            
            <h3 class="text-display-1 text-center margin-bottom-none">Add Student Fees</h3>
            <hr>
            <div class="panel-body">
                
                    <div class="form-group">
                        <label for="amount" class="col-sm-3 control-label">Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control text-center numbersOnly" name="amount" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="paid_by" class="col-sm-3 control-label">Paid By</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control text-center" name="paid_by" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="payment_date" class="col-sm-3 control-label">Payment Date</label>
                        <div class="col-sm-9">
                            <div class="input-group date">
                              <input type="text" readonly class="form-control datepicker text-center" name="payment_date">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="payment_mode" class="col-sm-3 control-label">Payment Mode</label>
                        <div class="col-sm-9">
                            <select id="payment_mode" name="payment_mode" class="form-control selectpickerz text-center">
                                
                                <?php
                                        
                                    //get the user types
                                    $query = "SELECT code, name FROM payment_modes ORDER BY name";
                                    $stmt = $db->conn->prepare($query);
                                    $stmt->execute();
                                    /* bind result variables */
                                    $stmt->bind_result($id, $name);
                                    
                                    while ($stmt->fetch()) 
                                    {
                                        echo "<option value='$id'>$name</option>";
                                    } 
                                
                                ?>
                                
                            </select>
                        </div>                                                
                    </div> 
                    <div class="form-group hidden-row" id="cheque-row">
                        <label for="cheque_no" class="col-sm-3 control-label">Cheque No</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control text-center" name="cheque_no">
                        </div>
                    </div>                                                        
                    
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                        <button type="submit" class="btn btn-info col-sm-12">Submit</button>
                        </div>
                    </div>
                
            </div>
            
        </div>
        
    </form>

</div>

<div class="col-sm-7">
    
    <div class="container-fluid">
                            
        <div class="panel panel-default text-center login-form-bg" data-z="0.5">
        
        <h3 class="text-display-1 text-center margin-bottom-none">Fees Summary</h3>
        <hr>
        <div class="panel-body">
            
            <div class="table-responsive" id="table-data" data-tbl="sch_fees_payments" data-tbl-pk="id">
            
                <table class="large-text table">
                    <tbody>
                        <tr>
                            <td>Required</td>
                            <td>Paid</td>
                            <td>Balance</td>
                        </tr>
                        
                        <tr>
                            <td class="text-info" id="fees_total">Required</td>
                            <td class="text-info" id="fees_paid">Paid</td>
                            <td class="text-success" id="fees_balance">Balance</td>
                        </tr>
                    </tbody>
                </table>
            
            </div>
        
        </div>
        
    </div>

    </div>
    
    <div class="container-fluid">
                            
        <div class="panel panel-default paper-shadow" data-z="0.5" id="papershadow">
            
            <div class="table-responsive2" id="table-data" data-tbl="sch_results_items" data-tbl-pk="id">
                                                                     
                <table id="fees-list" class="table table-striped table-responsive" width="100%" cellspacing="0" role="grid" style="width: 100%;">
                    
                    <thead>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Paid At</th>
                        <th>Paid By</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </thead>
                    
                    <tbody id="fees-data"></tbody>
                    
                    
                </table>

            </div>
        
        </div>

    </div>

</div>

<div class="clear"></div>

<div style='display:none'>
    
    <form class="form-horizontal form-edit-fee inputform" id="edit_fee_record">
                                                                          
            <div class="form-group">
            
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <h3>Edit Fees</h3>
                </div>
            
            </div>
                                                        
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="resultdiv"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="fee_amount" class="col-sm-3 control-label">Amount</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="fee_amount" id="fee_amount">
                    <input type="hidden" name="fee_payment_id" id="fee_payment_id">
                </div>
            </div>
           
            <div class="form-group">
                <label for="score" class="col-sm-3 control-label">Payment Mode</label>
                <div class="col-sm-9">
                    <select id="fee_payment_mode" name="fee_payment_mode" class="form-control selectpickerz">
                        
                        <?php
                                
                            //get the user types
                            $query = "SELECT code, name FROM payment_modes ORDER BY name";
                            $stmt = $db->conn->prepare($query);
                            $stmt->execute();
                            /* bind result variables */
                            $stmt->bind_result($id, $name);
                            
                            while ($stmt->fetch()) 
                            {
                                echo "<option value='$id'>$name</option>";
                            } 
                        
                        ?>
                        
                    </select>
                </div>  
            </div>
                                                           
            <div class="form-group">
                <label for="fee_paid_by" class="col-sm-3 control-label">Paid By</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="fee_paid_by" id="fee_paid_by">
                </div>
            </div>  
            
            <div class="form-group">
                <label for="fee_paid_at" class="col-sm-3 control-label">Payment Date</label>
                <div class="col-sm-9">
                    <div class="input-group date">
                      <input type="text" readonly class="form-control datepicker" name="fee_paid_at" id="fee_paid_at">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                    </div>
                </div>
            </div>
                                            
            <div class="form-group">
            
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                <button type="submit" class="btn btn-info col-sm-12">Submit</button>
                </div>
                
            </div>
                                                                                                                    
    </form>
    
</div>