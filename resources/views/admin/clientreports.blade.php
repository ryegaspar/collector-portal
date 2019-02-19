<!DOCTYPE html>
@extends('layouts.admin')
@section('title', ' - Client Reporting')
@section('topnavbar')
  <topnavbar title="Client Reporting"></topnavbar>
@endsection
@section('header')
<style>
.top-container {
color:#20A8D8;
font-weight:900;
}

.dateEntry{
color:#20A8D8;
font-weight:900;
}

.table-container {
height: 40em;
}

table {
  display: flex;
  flex-flow: column;
  height: 100%;
}

table thead {
  /* head takes the height it requires, 
  and it's not scaled when table is resized */
flex:1 1 auto;
width: 100%;
display: table;
table-layout:fixed;
}

table tbody {
    /* body takes all the remaining available space */
  flex: 0 1 auto;
  display: block;
  overflow-y: scroll;
  width:100%;
}

table tbody tr {
display: table;
table-layout:fixed;
width:100%;
}
/* decorations */
.table-container {
background-color:#FFFFFF; 
}

table th {
padding:.8em;
text-align:left;
}

table thead tr {
color:#E1FFFF;
background-color:#596473;
}

table th:hover {
color:#20A8D8;
cursor:pointer;
}

tbody tr:hover td {
background-color:#444D58;
color:#20A8D8;
cursor:pointer;
}

tbody td{
padding: .2em 0em .4em 1em;
vertical-align:middle;
border-bottom: solid 1px rgba(255,255,255,0.1);
font-weight:600;
}

table tr th:nth-child(6) {
  width:7%;
}

table tr td:nth-child(6) {
  width:5%;
}

tr:nth-child(even) {
background-color: #e9f7fc;
}

.highlight td {
background: #20A8D8;
color:white;
}

.checkBox  {
vertical-align:middle;
}

.showComponent{
cursor:pointer; 
}

.showComponent:hover{
cursor:pointer;
background-color: #15b8d3;
color:white;
} 

.showComponent:active{
cursor:pointer; 
}

.down {
transform: rotate(45deg);
-webkit-transform: rotate(45deg);
border: solid black;
border-width: 0 2px 2px 0;
display: inline-block;
padding: 3px;
float:right;
}

.suf {
 background-color: #bce6f5;
}

.presetButton {
background-color: #596473;
border: none;
color: white;
padding: 2% 2%;
font-size: 14px;
text-align: center;
text-decoration: none;
display: inline-block;
cursor: pointer;
width:23%;
}

.presetButton:hover {
background-color: #20A8D8;
color: white;
}

.runButton {
display: block;
width: 90%;
border: none;
background-color: #20A8D8;
padding: 2% 2%;
font-size: 16px;
cursor: pointer;
text-align: center;
color: white;
margin-left:2%;
}

.runButton:hover {
background-color: #48ba91;
color: white;

}
.runButton:active {
background-color: #48ba91;
}

#searchInput {
width: 85%;
font-size: 100%;
padding: 1% 1% 1% 2%;
border: 1px solid #ddd;
margin-bottom: 1%;
}






</style>

@endsection

@section('content')
  <!-- Main content -->
<main class="main">
  <div class="container-fluid">
    <form id="report_submit_form" method="post" action="/admin/clientreports">



<div class="top-container">
  
  <h1>Client Reporting</h1>

    <input type="text" id="searchInput" onkeyup="reportSearch()" placeholder="Search for Report.." title="Type in a report" />
    <br>

    <th>
      {{ csrf_field() }} Start Date: <input id="startDate"  type='text' name='date1' value="{{ old('date1') }}" /> End Date: <input id="endDate"  type='text' name='date2' value="{{ old('date2') }}" />
    </th>
</div> 






  <div class="table-container">
    <table id="reportTable">
      <thead>
        <tr>
          <th onclick="sortTable(0)">Report Title</th>
          <th onclick="sortTable(1)">Description</th>
          <th onclick="sortTable(2)">Cycle</th>
          <th onclick="sortTable(3)">Client</th>
          <th onclick="sortTable(4)">Last Ran Date</th>
          <th>Select</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>ffamPlacementAcknowledgement</td>
          <td>PlacementAcknowledgement</td>
          <td>NA</td>
          <td>FFAM</td>
          <td>Last Ran Date</td>
          <td><input type="checkbox" name="ffamPlacementAcknowledgement" class=checkBox /></td>
        </tr>
        
        <tr>
          <td>ASG Status</td>
          <td>Status</td>
          <td>Weekly</td>
          <td>Acceptance Solutions Group</td>
          <td>Last Ran Date</td>
          <td><input type="checkbox" name="asgStatus" class=checkBox /></td>
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
           <td>ASG Remit</td>
           <td>Remittance</td>
           <td>Bi Monthly</td>
           <td>Acceptance Solutions Group</td>
           <td>Last Ran Date</td>
           <td><input type="checkbox" name="asgRemit" value="Yes" class=checkBox  /></td>
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
          <td>Cascade Remit</td>
          <td>Remittance</td>
          <td>Weekly</td>
          <td>Cascade</td>
          <td>Last Ran Date</td>
          <td><input type="checkbox" name="cascadeRemit" value="Yes" class=checkBox  /></td>
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
           <td>CLA Status</td>
           <td>Status</td>
           <td>Monthly</td>
           <td>Community Loans of America</td>
           <td>Last Ran Date</td>
           <td><input type="checkbox" name="claStatus" value="Yes" class=checkBox  /></td>   
        </tr>

        <tr>
           <td>EOS Remit</td>
           <td>Remittance</td>
           <td>Bi Monthly</td>
           <td>Rocky Mountain Capital</td>
           <td>Last Ran Date</td>
           <td><input type="checkbox" name="eosRemit" value="Yes" class=checkBox  /></td>
        </tr>
        <tr>
           <td>Galaxy APAY</td>
           <td>Remittance</td>
           <td>Weekly</td>
           <td>Galaxy</td>
           <td>Last Ran Date</td>
           <td><input type="checkbox" name="galaxyAPAY" value="Yes" class=checkBox  /></td>
        </tr>

        <tr>
           <td>Galaxy Remit</td>
           <td>Remittance</td>
           <td>Weekly</td>
           <td>Galaxy</td>
           <td>Last Ran Date</td>
           <td><input type="checkbox" name="galaxyRemit" value="Yes" class=checkBox  /></td>
        </tr>

         <tr>
           <td>HCU Remit</td>
           <td>Remittance</td>
           <td>Bi Monthly</td>
           <td>HCU</td>
           <td>Last Ran Date</td>
           <td><input type="checkbox" name="hcuRemit" value="Yes" class=checkBox  /></td>
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
           <td>Plaza Remit</td>
           <td>Remittance</td>
           <td>Bi Monthly</td>
           <td>Plaza Services, LLC </td>
           <td>Last Ran Date</td>
           <td><input type="checkbox" name="pzaRemit" value="Yes" class=checkBox  /></td>
        </tr>

         <tr>
           <td>PLS Remit</td>
           <td>Remittance</td>
           <td>Bi Monthly</td>
           <td>PLS </td>
           <td>Last Ran Date</td>
           <td><input type="checkbox" name="plsRemit" value="Yes" class=checkBox  /></td>
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
           <td>Resurgent Recap</td>
           <td>Remittance</td>
           <td>Weekly</td>
           <td>Resurgent</td>
           <td>Last Ran Date</td>
           <td><input type="checkbox" name="resurgentRecap" value="Yes" class=checkBox  /></td>
        </tr>
           
        <tr>
           <td>Resurgent SUF File Monthly</td>
           <td onclick="makeVisible()" class="showComponent">Monthly SUF<i class="down"></i></td>
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
          
      </tbody>
    </table>
  </div>  <!-- End Content Div -->

   </br>
  
  <div class="footer">
      <div class=presetContainer>
      </br>
  
      <button type="button" class="presetButton" onClick="setDatesLastOne()">Yesterday</button>
      <button type="button" class="presetButton" onClick="setDatesLastThree()">Last Weekend</button>
      <button type="button" class="presetButton" onClick="setDatesLastSeven()">Last 7</button>
      <button type="button" class="presetButton" onClick="setDatesAutoDaily()">Daily Report</button>

    
      </div>   
        </br>

      <button type="submit" class="runButton" onClick="formSubmit()">Run</button>
   
  </div> 



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


</div>
  </main>
@endsection
@section('footer')



<!-- Allows for searching through report list -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("reportTable");
  switching = true;
  dir = "asc"; 

  while (switching) {
    switching = false;
    rows = table.rows;
    for (i = 1; i < (rows.length - 1); i++) {
      shouldSwitch = false;
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchcount ++;      
    } else {
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>

<script>
$(document).ready(function () {
    $('tbody tr').click(function (event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });

    $("input[type='checkbox']").change(function (e) {
        if ($(this).is(":checked")) {
            $(this).closest('tr').addClass("highlight");
        } else {
            $(this).closest('tr').removeClass("highlight");
        }
    });
});

</script>

<script>
function reportSearch() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("reportTable");
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
<script>
$(document).ready(function() {
  $('.suf').hide();

  
});
function makeVisible() {
    $('.suf').toggle();
}

function formSubmit(e) {
    $("#report_submit_form").submit();
  }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@endsection