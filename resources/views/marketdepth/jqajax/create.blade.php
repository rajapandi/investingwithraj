<style>
    
.search-field, .term-list {
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
}

.search-field {
  display: block;
  width: 100%;
  margin: 1em auto 0;
  /*padding: 0.5em 10px;*/
  border: 1px solid #999;
  font-size: 100%;
  font-family: "Arvo", "Helvetica Neue", Helvetica, arial, sans-serif;
  font-weight: 400;
  color: #3e8ce0;
}

.term-list {
    position:absolute;
  list-style: none inside;
  width: 60%;
  height:350px;
  margin: 0 auto 2em;
  padding: 5px 10px 0;
  text-align: left;
  color: #777;
  background: #fff;
  border: 1px solid;
  font-family: "Arvo", "Helvetica Neue", Helvetica, arial, sans-serif;
  font-weight: 400;
  overflow-y:scroll;
  z-index: 99999;
}
.term-list li {
  padding: 0.5em 0;
  border-bottom: 1px solid #eee;
}
.term-list strong {
  color: #444;
  font-weight: 700;
}

.hidden {
  display: none;
}

</style>

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Create Market </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row depth-table">
        <form action="/create/market-depth" method="post">
            @csrf
            <table class="table table-bordered">
                <tr>
                    <td>Exchange</td>
                    <td><select name="exchange" id="exchange" class="form-control">
                            <option value="NSE" selected="">NSE</option>
                            <option value="BSE">BSE</option>
                            <option value="MCX">MCX</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Symbol</td>
                    <td><input type="text" class="form-control"  id="searchBox" name="tradingsymbol" onkeyup="getSearch(this.value)" autocomplete="off" required>
                        <ul id="searchResults" class="term-list hidden"></ul></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn btn-success btn-sm"  style="float: right;">Save</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
    function getSearch(str){
     if(str.length<2){
        $("#searchResults").css({'display':'none'});  
        $('#searchResults').html("");
     }else{
         $.get('/trade/searchTradeSymbol', {key:str}, function(result){
             $("#searchResults").css({'display':'block'});  
             $('#searchResults').html("");
             $('#searchResults').append(result);
         });
     }
 }

 function getAddDataOnSerachBox(str){
    $('#searchBox').val("");
    $('#searchBox').val(str);
    $("#searchResults").css({'display':'none'});  
    $('#searchResults').html("");
    getSearch().stop();
    
}
</script>