      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Price Base Schedule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table>
            <tr>
                <td>Price Type</td>
                <td>
                    <select name="price_type" id="price_type" class="form-control">
                        <option value="">Price Type</option>
                        <option value="LTP">LTP</option>
                        <option value="BID">BID PRICE</option>
                        <option value="ASK">ASK PRICE</option>
                    </select>
                </td>
                <td><input type="text" name="schedular_price" id="schedular_price" class="form-control" placeholder="Price"></td>
            </tr>
            <tr>
                <td>Comparison</td>
                <td colspan="2">
                    <select name="below_above" id="below_above" class="form-control">
                        <option value="">Comparison</option>
                        <option value="Above">Above-Equals</option>
                        <option value="Below">Below-Equals</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Set Price</td>
                <td colspan="2"><input type="text" name="set_price" id="set_price" placeholder="Set Price" class="form-control"></td>
            </tr>
            <tr>
                <td>No Of Order</td>
                <td colspan="2"><input type="text" name="no_of_order" id="no_of_order" placeholder="No of Order" class="form-control"></td>
            </tr>
            <tr>
                <td>Validity</td>
                <td>
                    <select name="validity_type" id="validity_type" class="form-control">
                        <option value="">Validity Type</option>
                        <option value="DAY">DAY</option>
                        <option value="GTT">GTT</option>
                    </select>
                </td>
                <td><input type="text" name="schedular_validity" id="schedular_validity" placeholder="Validity" class="form-control"></td>
            </tr>
            <tr>
                <td colspan="3"><span style="float: right"><button class="btn btn-info btn-sm" onclick="setPriceBaseSchedular()">Set</button></span></td>
            </tr>
        </table>
      </div>


  <script>
    $(document).ready(function() {

  });
  </script>
