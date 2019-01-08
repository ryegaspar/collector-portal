<!DOCTYPE html>
<html>
<head>
  <title>Unifin Reports </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}
body {
    font-family: "Lato", sans-serif;
}

td {
    font-family: "Lato", sans-serif;
    font-size: 13px;
}

th {
    font-family: "Lato", sans-serif;
    font-size: 15px;
    cursor:pointer; 
}

#myInput {
  width: 80%;
  font-size: 100%;
  padding: 1% 2% 1% 4%;
  border: 1px solid #ddd;
  margin-bottom: 1%;
}
#myTable {
  border-collapse: collapse;
  width: 80%;
  border: 1px solid #ddd;
  font-size: 100%;
}
#myTable th{ 
  background-color: #88c4ce;
  text-align: left;
  border: 1px solid #ddd;
  padding: 8px;
  color:white;
}


#myTable td {
  text-align: left;
  border: 1px solid #ddd;
  padding: 8px;
}

#myTable tr:hover {background-color: #15b8d3;
}
#myTable tr:active {background-color: #48ba91;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

.runButton {
    display: block;
    width: 80%;
    border: none;
    background-color: #48aaba;
    
   padding: 2% 2%;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    color: white;

}
.runButton:hover {
  background-color: #48ba91;
  color: white;

}
.runButton:active {
  background-color: #48ba91;
}
.date{
width: 80%;
background-color: #88c4ce;
padding: 2%;
color:white;
}
.presetButton {
    background-color: #88c4ce;
    border: none;
    color: white;
    padding: 2% 2%;
    font-size: 100%;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    width:19%;
}
.presetButton:hover {
background-color: #15b8d3;
color: white;

}
.top{
  width:80%;
  color:#15b8d3;
}
.showComponent{
  cursor:pointer; 
}
.showComponent:hover{
  cursor:pointer;
  background-color: #15b8d3;
  color: white;
} 
.showComponent:active{
  cursor:pointer; 
}
.down {
  transform: rotate(45deg);
  -webkit-transform: rotate(45deg);
}
.suf  {
background-color: #dcedef;
}


i {
  border: solid black;
  border-width: 0 3px 3px 0;
  display: inline-block;
  padding: 3px;
  float:right;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
  $('.suf').hide();
});
function makeVisible() {
    $('.suf').toggle();
}
</script>

</head>

<body>

<div class="top">
<h1>Unifin Reports</h1>
</div>
<input type="text" id="myInput" onkeyup="reportSearch()" placeholder="Search for Report.." title="Type in a report">
  
    </br>
    </br>

<div class="date">
    <th><form method="post" action="/admin/clientreports">
    {{ csrf_field() }}
    
    Start Date:
    <input id="startDate" type='text' name='date1' value="{{ old('date1') }}" />
    End Date:
    <input id="endDate" type='text' name='date2' value="{{ old('date2') }}" />
    </th>
  
  </div>




<table id ="myTable">
  


  <tr class="header">
  <th onclick="sortTable(0)" >Report Title</th>
  <th onclick="sortTable(1)">Description</th>
  <th onclick="sortTable(2)">Cyle</th>
  <th onclick="sortTable(3)">Client</th>
  <th onclick="sortTable(4)">Last Ran Date</th>
  <th>Select</th>
  </tr>


  <tr>
   <td>ASG Daily Payments</td>
   <td>Payment</td>
   <td>Daily</td>
   <td>Acceptance Solutions Group</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="asgPayFile" value="Yes" class=checkBox  /></td>
  </tr>

  <tr>
   <td>ASG Status</td>
   <td>Status</td>
   <td>Weekly</td>
   <td>Acceptance Solutions Group</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="asgStatus" value="Yes" class=checkBox  /></td>
  </tr>

  <tr>
  <td>Capio Remit</td>
  <td>Remittance</td>
  <td>Weekly</td>
  <td>Capio Partners, LLC</td>
  <td>Last Ran Date</td>
  <td><input type="checkbox" name="capioRemit" value="Yes" class=checkBox  /></td>
  </tr>

  <tr>
   <td>CLA Daily Payments</td>
   <td>Payment File</td>
   <td>Daily</td>
   <td>Community Loans of America</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="claPayFile" value="Yes" class=checkBox  /></td>   
  </tr>

   <tr>
   <td>EOS Remit</td>
   <td>Remittance</td>
   <td>Bi Monthly</td>
   <td>Rocky Mountain Capital</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="eosRemit" value="Yes" class=checkBox  /></td>
  </tr>

   <!-- <tr>
   <td>JcapMaintenance</td>
   <td>Maintenance</td>
   <td>Weekly</td>
   <td>Jefferson Capital Systems, LLC</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="jcapMaintenance" value="Yes" class=checkBox  /></td>
  </tr>
 -->

  <!-- <tr>
   <td>JCAP RecUni</td>
   <td>Remittance</td>
   <td>Weekly</td>
   <td>Jefferson Capital Systems, LLC</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="jcapRecUni" value="Yes" class=checkBox  /></td>
  </tr> -->

<tr>
  <td>LTD Remit</td>
  <td>Remittance</td>
  <td>Weekly</td>
  <td>LTD Financial Services</td>
  <td>Last Ran Date</td>
  <td><input type="checkbox" name="ltdRemit" value="Yes" class=checkBox  /></td>
  </tr>

<tr>
   <td>MUSI Remit</td>
   <td>Remittance</td>
   <td>Monthly</td>
   <td>Musicians Institute</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="musiRemit" value="Yes" class=checkBox  /></td>
  </tr>

  <tr>
   <td>Orion Remit</td>
   <td>Remittance</td>
   <td>Monthly</td>
   <td>Orion</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="orionRemit" value="Yes" class=checkBox  /></td>
  </tr>

<tr>
  <td>Pendrick Invoice Main</td>
  <td>Remittance</td>
  <td>Weekly</td>
  <td>Pendrick Capital Partners</td>
  <td>Last Ran Date</td>
  <td><input type="checkbox" name="pendrickMainInvoice" value="Yes" class=checkBox  /></td>
  </tr>
 
 <tr>
  <td>Pendrick Invoice PCP2</td>
  <td>Remittance</td>
  <td>Weekly</td>
  <td>Pendrick Capital Partners II</td>
  <td>Last Ran Date</td>
  <td><input type="checkbox" name="pendrickPcp2Invoice" value="Yes" class=checkBox  /></td>
  </tr>

  <tr>
  <td>Pendrick Indirect Payments PCP2</td>
  <td>Payment</td>
  <td>Daily</td>
  <td>Pendrick Capital Partners II</td>
  <td>Last Ran Date</td>
  <td><input type="checkbox" name="pendrickIndirectPaymentsPcp2" value="Yes" class=checkBox  /></td>
  </tr>

  <tr>
  <td>Pendrick Indirect Payments MAIN</td>
  <td>Payment</td>
  <td>Daily</td>
  <td>Pendrick Capital Partners</td>
  <td>Last Ran Date</td>
  <td><input type="checkbox" name="pendrickIndirectPaymentsMain" value="Yes" class=checkBox /></td>
  </tr>



  <tr>
  <td>Renaissance Remit</td>
  <td>Remittance</td>
  <td>Weekly</td>
  <td>Renaissance Trade Capital</td>
  <td>Last Ran Date</td>
  <td><input type="checkbox" name="rtcRemit" value="Yes" class=checkBox  /></td>
  </tr>

  <tr>
   <td>Resurgent Forcast</td>
   <td>Forecast</td>
   <td>Weekly</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentFct" value="Yes" class=checkBox  /></td>
  </tr>

  <tr>
   <td>Resurgent Remit</td>
   <td>Remittance</td>
   <td>Weekly</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentRemit" value="Yes" class=checkBox  /></td>
  </tr>
   <tr>
   <td onclick="makeVisible()" class="showComponent">Resurgent SUF File Monthly <i class="down"></i></td>
   <td>Monthly SUF</td>
   <td>Monthly</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentSufMonthly" value="Yes" class=checkBox  /></td>
  </tr>


  <tr class = "suf">
   <td>Resurgent ABL</td>
   <td>Balance</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentAbl" value="Yes" class=checkBox  /></td>
  </tr>
  
  <tr class="suf">
   <td>Resurgent BKY</td>
   <td>Bankrupcty</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentBky" value="Yes" class=checkBox  /></td>
  </tr>

  <tr class="suf">
   <td>Resurgent BWR</td>
   <td>Borrower</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentBwr" value="Yes" class=checkBox  /></td>
  </tr>
  
  <tr class="suf">
   <td>Resurgent DEC</td>
   <td>Deceased Record</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentDec" value="Yes" class=checkBox  /></td>
  </tr>
  
  <tr class="suf">
   <td>Resurgent KPI</td>
   <td>KPI</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentKpi" value="Yes" class=checkBox  /></td>
  </tr>
 
  <tr class="suf">
   <td>Resurgent PDC</td>
   <td>Post Dated Checks</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentPdc" value="Yes" class=checkBox  /></td>
  </tr>
 
  <tr class="suf">
   <td>Resurgent WOR</td>
   <td>Workflow</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentWor" value="Yes" class=checkBox  /></td>
  </tr>


  <tr>
   <td>Resurgent SUF File Weekly</td>
   <td>Weekly SUF</td>
   <td>Weekly</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentSufWeekly" value="Yes" class=checkBox  /></td>
  </tr>
  
  <tr>
   <td>Resurgent SUF File Daily</td>
   <td>Daily SUF</td>
   <td>Daily</td>
   <td>Resurgent</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="resurgentSufDaily" value="Yes" class=checkBox  /></td>
  </tr>


  <tr>
   <td>RMC Remit</td>
   <td>Remittance</td>
   <td>Bi Monthly</td>
   <td>Rocky Mountain Capital</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="rmcRemit" value="Yes" class=checkBox  /></td>
  </tr>

  <tr>
   <td>RTC Remit</td>
   <td>Remittance</td>
   <td>Bi Monthly</td>
   <td>Renaissance Trade Captial</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="rtcRemit" value="Yes" class=checkBox  /></td>
  </tr>


  <tr>
   <td>WCR Remit</td>
   <td>Remittance</td>
   <td>Bi Monthly</td>
   <td>World Credit Recovery</td>
   <td>Last Ran Date</td>
   <td><input type="checkbox" name="wcrRemit" value="Yes" class=checkBox  /></td>
  </tr>
  

</table>



</br>


    <button type="button" class="presetButton" onClick="setDatesLastOne()">Yesterday</button>
    <button type="button" class="presetButton" onClick="setDatesLastThree()">Last Weekend</button>
    <button type="button" class="presetButton" onClick="setDatesLastSeven()">Last 7</button>
    <button type="button" class="presetButton" onClick="setDatesAutoDaily()">Daily Report</button>
  </br>
  </br>
    <button type="submit" class="runButton">Run</button>
    
  
  </form>

  @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
    </div>
  @endif





<!-- Allows for searching through report list -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>



<script>
function reportSearch() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function setDatesLastOne() {
  var startDate = moment().add(-1, 'day').format('YYYY-MM-DD');
  var endDate = moment().add(-1, 'day').format('YYYY-MM-DD');
  
  document.getElementById('startDate').value = startDate;
  document.getElementById('endDate').value = endDate;
}


function setDatesLastThree() {
  var startDate = moment().add(-3, 'day').format('YYYY-MM-DD');
  var endDate = moment().add(-1, 'day').format('YYYY-MM-DD');
  document.getElementById('startDate').value = startDate;
  document.getElementById('endDate').value = endDate;
}

function setDatesLastSeven() {
  var startDate = moment().add(-7, 'day').format('YYYY-MM-DD');
  var endDate = moment().add(-1, 'day').format('YYYY-MM-DD');
  document.getElementById('startDate').value = startDate;
  document.getElementById('endDate').value = endDate;
}

function setDatesAutoDaily() {
  /*Set current day date*/
  var todayDate = moment().format('YYYY-MM-DD');
  
  if (todayDate == moment().isoWeekday("Monday")) {

  var startDate = moment().add(-3, 'day').format('YYYY-MM-DD');
  var endDate = moment().add(-1, 'day').format('YYYY-MM-DD');
  document.getElementById('startDate').value = startDate;
  document.getElementById('endDate').value = endDate;
  
  } else {  

  var startDate = moment().add(-1, 'day').format('YYYY-MM-DD');
  var endDate = moment().add(-1, 'day').format('YYYY-MM-DD');
  document.getElementById('startDate').value = startDate;
  document.getElementById('endDate').value = endDate;
}

}

</script>

</body>
</html>
