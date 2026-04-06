function ankieta(formularz) // Tomek B 30-08-2005   
{
a=0;
b=0;
c=0;
d=0;
e=0;
f=0;
g=0;
h=0;
licznik=formularz.elements.length;
for (i=0;i<licznik;i++){
if (formularz.elements[i].checked){
    if(formularz.elements[i].value=='a'){
     a=a+1;
	 }
    if (formularz.elements[i].value=='b'){
     b=b+1;
	 }
    if (formularz.elements[i].value=='c'){
     c=c+1;
	 }
	 if(formularz.elements[i].value=='d'){
     d=d+1;
	 }
    if (formularz.elements[i].value=='e'){
     e=e+1;
	 }
    if (formularz.elements[i].value=='f'){
     f=f+1;
	 }
	 if (formularz.elements[i].value=='g'){
     g=g+1;
	 }
	 if (formularz.elements[i].value=='h'){
     h=h+1;
	 }
	 }
}
	rezultat=window.open('','rezultat','width=330,height=300,toolbar=1,status=0,directories=0,location=0,scrollbars=0,resizable=0,left=0,top=0');
	rezultat.document.writeln("<HTML>");
	rezultat.document.writeln("<HEAD>");
	rezultat.document.writeln("<TITLE>:: TWÓJ PROFIL INTELIGENCJI WIELORAKICH ::</TITLE>");
	rezultat.document.writeln("<LINK REL='stylesheet' HREF='act.css' TYPE='text/css'>");
	rezultat.document.writeln("<script language='JavaScript' src='opis.js'></script>");
	rezultat.document.writeln("<META HTTP-EQUIV='Content-type' CONTENT='text/html; charset=iso-8859-2'>");
	rezultat.document.writeln("</HEAD>");
	rezultat.document.writeln("<BODY BGCOLOR='#FBE4B8'>");
	rezultat.document.writeln("<table width='300' border='0' cellpadding='0' cellspacing='0'><tr align='center'><td colspan='11'><img src='images/wynik_header.gif' width='300' height='40'></td></tr>");
	rezultat.document.writeln("<tr><td width='254' CLASS='text'><a href='opisy_mi/jezykowa.html')'>jêzykowa</a></td><td colspan='10'><img src='images/wynik_a.jpg' width="+10*a+" height='15'></td></tr>");
	rezultat.document.writeln("<tr><td colspan='11' CLASS='text'><img src='images/belka.gif' width='300' height='3'></td></tr>");
	rezultat.document.writeln("<tr><td CLASS='text'><a href='opisy_mi/matlog.html'>logiczno - matematyczna</a></td><td colspan='10'><img src='images/wynik_b.jpg' width="+10*b+" height='15'></td></tr>");
	rezultat.document.writeln("<tr><td colspan='11' CLASS='text'><img src='images/belka.gif' width='300' height='3'></td></tr>");
	rezultat.document.writeln("<tr><td CLASS='text'><a href='opisy_mi/wizualna.html'>wizualno - przestrzenna</a></td><td colspan='10'><img src='images/wynik_c.jpg' width="+10*c+" height='15'></td></tr>");
	rezultat.document.writeln("<tr><td colspan='11' CLASS='text'><img src='images/belka.gif' width='300' height='3'></td></tr>");
	rezultat.document.writeln("<tr><td CLASS='text'><a href='opisy_mi/muzyczna.html'>muzyczna</a></td><td colspan='10'><img src='images/wynik_d.jpg' width="+10*d+" height='15'></td></tr>");
	rezultat.document.writeln("<tr><td colspan='11' CLASS='text'><img src='images/belka.gif' width='300' height='3'></td></tr>");
	rezultat.document.writeln("<tr><td CLASS='text'><a href='opisy_mi/kinestetyczna.html'>kinestetyczna</a></td><td colspan='10'><img src='images/wynik_e.jpg' width="+10*e+" height='15'></td></tr>");
	rezultat.document.writeln("<tr><td colspan='11' CLASS='text'><img src='images/belka.gif' width='300' height='3'></td></tr>");
	rezultat.document.writeln("<tr><td CLASS='text'><a href='opisy_mi/inter.html'>interpersonalna</a></td><td colspan='10'><img src='images/wynik_f.jpg' width="+10*f+" height='15'></td></tr>");
	rezultat.document.writeln("<tr><td colspan='11' CLASS='text'><img src='images/belka.gif' width='300' height='3'></td></tr>");
	rezultat.document.writeln("<tr><td CLASS='text'><a href='opisy_mi/intra.html'>intrapersonalna</a></td><td colspan='10'><img src='images/wynik_g.jpg' width="+10*g+" height='15'></td></tr>");
	rezultat.document.writeln("<tr><td colspan='11' CLASS='text'><img src='images/belka.gif' width='300' height='3'></td></tr>");
	rezultat.document.writeln("<tr><td CLASS='text'><a href='opisy_mi/przyrodnicza.html'>przyrodnicza</a></td><td colspan='10'><img src='images/wynik_h.jpg' width="+10*h+" height='15'></td></tr>")
	rezultat.document.writeln("<tr bgcolor='#CACAFF'><td></td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>1</td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>2</td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>3</td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>4</td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>5</td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>6</td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>7</td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>8</td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>9</td>");
	rezultat.document.writeln("<td width='10' bgcolor='#CACAFF' CLASS='skala'>10</td></tr></table>");
	rezultat.document.writeln("</BODY>");
	rezultat.document.writeln("</HTML>");
	rezultat.document.close();
	rezultat.focus();
}