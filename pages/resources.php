<html>
<head>
  <title>RAD Makerspace - Resources</title>
  <link rel="icon" href="/img/Goomerbot Logo3.png">
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="/js/functions.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script>
    function price_Calculator()
      {
          var grams = document.getElementById("grams").value;
          var time = document.getElementById("time").value;
          var cost = Math.round(((grams*(.053) + time*(.001) + 3)*100)) / 100;
          document.getElementById("cost").value = "$" + cost;
      }
    </script>
</head>
<body>
  <div id="Nav">
  </div>
<center><h3>Resources</h3>
<div class="container">
<table class="table table-condensed">
<tbody>
<tr>
<td>
<h3>Utilizing the Makerspace</h3>
<p><u><strong>Requesting a Print as a Student:</strong></u></p>
<ul>
<li>Personal prints must be submitted with a completed print request form. The form and the accompanying process are listed below.</li>
</br>
<li>Club/RSO prints are identical to personal requests.</li>
</br>
<li>Academic projects need only the course and teacher name submitted with the request. Prints for academic purposes are free.</li>
</br>
<li>Research projects need only the project details and the faculty sponsor name submitted with the request. Prints for research purposes are free.</li>
</ul>
</br>
<strong>Note</strong> students must complete the 3D Printing <a href="https://successprimers.instructure.com/courses/2" target="_blank">Success Primers</a> course with a minimum grade of 70 before printing for any purpose.</br>
<strong>Note</strong> The 3D files to print must be provided with every request. Only .stl's will be accepted.</br>
<strong>Note</strong> students receive one free print upon passing the primers course. The free print must fit on one 3d printer build plate. The dimension constraints are 25.2 L x 19.9 W x 15.0 H cm [9.9 L x 7.8 W x 5.9 H in].</br></br>
<p><u><strong>Requesting a Print as a Faculty member:</strong></u></p>
<ul>
<li>Personal and Event (includes all trophies, awards, give-a-ways, raffles, auctions, etc.) prints must be submitted to <a href="mailto:sjohnson@flpoly.org?Subject=3D%20Printing%20Question">Scott Johnson</a> along with either the department name and charge code for Workday or a completed print request form. The form and the accompanying process are listed below.</li>
</br>
<li>Teaching Aid prints must be submitted to <a href="mailto:sjohnson@flpoly.org?Subject=3D%20Printing%20Question">Scott Johnson</a>.  Teaching aid prints are free.</li>
</ul>
</br>
<p><u><strong>Paying for a print using the 3d print request form:</strong></u></p>
<ul>
<li>A <a href="https://pulse.floridapolytechnic.org/wp-content/uploads/2016/10/3d_print_request.pdf" target="_blank"><i class="fa fa-plus"></i>3d print request</a> form must be filled out and sent along with the initial request.</li>
</br>
<li> If the project is approved a cost will be generated and the form will be emailed back with the price.</li>
</br>
<li>This form will then need to be shown to the cashier at the wellness center, along with payment, who will give the requestor a receipt.</li>
</br>
<li>Printing will begin once this receipt is shown to an employee at the Makerspace as proof of payment.</li>
</ul>
</td>
<tr>
<td>
<h3>Lab Hours</h3>
<p><u><strong>Spring 2017</strong></u></p>
<ul>
<li>Monday, Tuesday & Friday 10AM - 5PM</li>
<li>Wednesday & Thursday 1PM - 7PM</li>
</ul>
<strong>Note</strong> the lab closes 12PM - 1PM for lunch every weekday
</td>
</tr>
<tr>
<td>
<h3>FAQ</h3>
<p>
<strong>How can students and faculty use the non-standard 3D printers in the makerspace?</strong>
</br>
The Stratasys, Deltamakers, Z18's and older Makerbot printers are available for use. Due to the extra time and cost needed to setup these printers their use is discouraged unless needed. Talk to a lab intern or the lab manager for more details about using them.
</br></br>
<strong>Can I come in the lab?</strong>
</br>
Yes, during lab hours. The lab has several computers with CAD and slicing software installed for students to work on their model. The student workers additionally are available for questions.
</br></br>
<strong>Can I print anything besides PLA?</strong>
</br>
Yes and no. All but one printer in the lab is equipped to print PLA. The requirements for other plastics are not met with these. The stratsys is capable of printing ABS however, printing with this printer is discouraged unless necessary. Talk to a lab intern or the lab manager for more details about using it.
</br></br>
<strong>Can I use any of the tools in the lab to cleanup my print or work on something?</strong>
</br>
No, initially this was tried however, several tools went missing so for this reason no tools are to be used unless by those working in the lab.
</br></br>
<strong>How can I work in the Makerspace</strong>
</br>
tba
</br></br>
</p>
</td>
</tr>
<tr>
<td>
<h3>Print Cost Estimator</h3>
<p>
This is the same tool we use to calculate the cost of a print request.
Before submitting a request, students can use this tool to get a rough approximation of how much they will be charged.
The value of the following fields can be found when using the "print preview" option in Makerbot Desktop.</br>
<div>
<p><input id="grams" onkeyup="price_Calculator()" placeholder="grams"> Total plastic needed</p>
<p><input id="time" onkeyup="price_Calculator()" placeholder="minutes"> Total print time</p>
<p><input id="cost" > Total cost</p>
<div>
<strong>Note</strong> any number obtain through this price calculator is highly dependent on preparation of the STL file(s)
and is therefore not a guarantee of the final quoted cost of the request.
</p>
</td>
</tr>
<tr>
<td>
<h3>Documents</h3>
<p>
<a href="https://pulse.floridapolytechnic.org/wp-content/uploads/2016/10/3d_print_request.pdf" target="_blank">3d print request</a><span> pdf</span></br>
<a href="https://pulse.floridapolytechnic.org/wp-content/uploads/2016/04/event-proposal.pdf" target="_blank">event-proposal</a><span> pdf</span></br>
<a href="https://pulse.floridapolytechnic.org/wp-content/uploads/2016/04/3D-Checklist.pdf" target="_blank">3D Checklist</a><span> pdf</span></br>
<a href="https://pulse.floridapolytechnic.org/wp-content/uploads/2016/04/Creating-a-Student-Poly-Primers-Canvas-Account.pdf" target="_blank">Creating a Student Poly Primers Canvas Account</a><span> pdf</span></br>
<a href="https://pulse.floridapolytechnic.org/wp-content/uploads/2016/09/Primers-Login-Problems.pdf" target="_blank">primers-login-problems</a><span> pdf</span></br>
</p>
</td>
</tr>
<tr>
<td>
<h3>3D Software</h3>
<p>
<a href="https://tinkercad.com/" target="_blank">TinkerCAD</a> – Free web based 3D creation software.</br>
<a href="https://www.makerbot.com/desktop" target="_blank">Makerbot Desktop</a> – Needed to prepare an STL or OBJ file for printing.</br>
<a href="https://modelrepair.azurewebsites.net/" target="_blank">Netfabb File Fixer</a> – Handy web based tool for repairing STL files.</br>
<a href="http://www.autodesk.com/education/free-software/all" target="_blank">Autodesk for Education</a> – Educational program by Autodesk that will give you free software tools.</br>
<a href="http://www.meshmixer.com/download.html" target="_blank">Meshmixer</a> – Free 3D editor and file repair tool.</br>
<a href="https://grabcad.com/library" target="_blank">GrabCAD</a> (Free models)</br>
<a href="https://www.solidworks.com/sw/community.htm" target="_blank">Solidworks Community</a> (Free Models)</br>
<a href="https://tinkercad.com/" target="_blank">TinkerCAD Gallery</a> (Free Models)</br>
<a href="https://www.creativecrash.com/3dmodels" target="_blank">Creative Crash</a> (Free Models)</br>
<a href="http://nasa3d.arc.nasa.gov/" target="_blank">NASA</a> (Free Models)</br>
</p>
</td>
</tr>
<tr>
<td>
<h3>Contacts</h3>
<p>
<a href="mailto:sjohnson@flpoly.org?Subject=3D%20Printing%20Question">Scott Johnson</a> - Lab Manager</br>
<a href="mailto:makerspace@flpoly.org?Subject=3D%20Printing%20Question">Makerspace</a> - The official email account for the RAD makerspace</br></br>
<a href="https://www.instagram.com/rad_makerspace/" target="_blank"><img src="https://pulse.floridapolytechnic.org/wp-content/uploads/2016/05/istagram300.png" width="193" height="64">
</a>
</p>
</td>
</tr>
</tbody>
</table>
</div>
<nav class="navbar navbar-default navbar-fixed-bottom">
  <footer>
    <p>Developer: William J Irwin <img src="/img/logoOriginal.jpg" width="50" height="50"></p>
    <p>Contact information: <a href="mailto:william.j.irwin10@gmail.com">
    william.j.irwin10@gmail.com</a>.</p>
    </footer>
</nav>
</body>
</html>
