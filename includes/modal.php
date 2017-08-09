<div class="modal grow modal-backdrop-white fade" id="modal-update-credit-card">
    <div class="modal-dialog modal-sm">
        <div class="v-cell">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Update Credit Card</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group form-control-material">
                            <input type="text" class="form-control" id="credit-card" placeholder="**** **** **** 2422">
                            <label for="credit-card">Credit Card</label>
                        </div>
                        <div class="form-group">
                            <label for="exp">Expiration Date:</label>
                            <br/>
                            <select id="exp" data-toggle="select2">
                                <option value="1" selected>January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <select data-toggle="select2">
                                <option value="2015" selected>2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                            </select>
                        </div>
                        <div class="form-group form-control-material">
                            <input type="text" class="form-control" id="cvv" placeholder="123">
                            <label for="cvv">CVV</label>
                        </div>
                        <button type="submit" class="btn btn-success paper-shadow relative" data-z="0.5" data-hover-z="1" data-animated data-dismiss="modal">Update Credit Card</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal grow modal-backdrop-white fade" id="modal-general">
    <div class="modal-dialog modal-sm">
        <div class="v-cell">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="general-modal-title">Modal Title</h4>
                </div>
                <div class="modal-body" id="general-modal-body">
                    Modal Body
                </div>
            </div>
        </div>
    </div>
</div>