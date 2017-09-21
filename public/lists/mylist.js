/*

Author: 2283336341634043456338834323128329634043460346034443448
this is the javascript for menu.html
Created: October 20, 2014
Updated: June 6, 2015

All functions within this js file are 
Copyright 2014 2283336341634043456338834323128329634043460346034443448 All Rights Reserved
No part or portion may be copied or used in any other electronic
system, including but not limited to a program, essay,
research paper, databases, tables, word processors or other storage media,
for any purpose without written permission from
2283336341634043456338834323128329634043460346034443448
*/
function getOldPass(pass) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var curPass = pass.trim();
	var dkey = (parseInt(pass.substr(1, parseInt(pass.charAt(0)))))/7;
	var i=0;
	var tChar = 0;
	var tmpChar="";
	var newPassword="";
	//alert("Lenght: " + curPass.length);
	for (i=parseInt(pass.charAt(0))+1; i < curPass.length; i++) {
		tChar = parseInt(curPass.charAt(i)); // what is the length of the next character
		//substr(start, length)
		//String.fromCharCode(65); 
		tmpChar=String.fromCharCode(((parseInt(curPass.substr(i+1, tChar)))/dkey));
		i += (tChar);
		newPassword += tmpChar;
		
	}
	//alert("new pass is: "+newPassword);
	return newPassword;
}

function getPass(pass) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var curPass = pass.trim();
	var dkey = (parseInt(pass.substr(1, parseInt(pass.charAt(0)))))/7;
	var i=0;
	var tChar = 0;
	var tmpChar="";
	var newPassword="";
	//alert("Lenght: " + curPass.length);
	for (i=parseInt(pass.charAt(0))+17; i < curPass.length; i++) {
		tChar = parseInt(curPass.charAt(i)); // what is the length of the next character
		//substr(start, length)
		//String.fromCharCode(65); 
		tmpChar=String.fromCharCode(((parseInt(curPass.substr(i+1, tChar)))/dkey));
		i += (tChar+16);
		newPassword += tmpChar;
	}
	return newPassword;
}

function getInfo() {
	// copyright 2015 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var pass = document.getElementById("userPswrd").value;
	if (pass == "" || pass==null) {
		document.getElementById("passwrd").innerHTML="Your entry was blank";
		document.getElementById("passwrd").style.color="red";
		return;
	} else {
		var newPass=getPass(pass);
		document.getElementById("passwrd").innerHTML=newPass;
		document.getElementById("passwrd").style.color="#DBDBDB";
	}
}

function getOldInfo() {
	// copyright 2015 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var pass = document.getElementById("olduserPswrd").value;
	if (pass == "" || pass==null) {
		document.getElementById("passwrd").innerHTML="Your entry was blank";
		document.getElementById("passwrd").style.color="red";
		return;
	} else {
		
		document.getElementById("passwrd").innerHTML=getOldPass(pass);
		document.getElementById("passwrd").style.color="#DBDBDB";
	}
}

function oldgetLogPass(key, pass) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var curPass = pass;
	var dkey = key;
	var i=0;
	var tChar = 0;
	var tmpChar = "";
	var newPassword="";
	for (i=parseInt(pass.charAt(0))+1; i < curPass.length; i++) {
		tChar = parseInt(curPass.charAt(i)); // what is the length of the next character
		//substr(start, length)
		//String.fromCharCode(65); 
		tmpChar=String.fromCharCode(((parseInt(curPass.substr(i+1, tChar)))/dkey));
		i += tChar;
		newPassword += tmpChar;
	}
	return newPassword;
}

function initForm() {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var frm=document.getElementById("myForm");
	frm.btnSubmit.style.display="inline";
	frm.name.style.display="inline";
	frm.subject.style.display="inline";
	frm.email.style.display="inline";
	frm.phone.style.display="inline";
	frm.message.style.display="inline";
}

function checkError(objName) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var debug = false;
	var formError=false;
	var inputType = objName;
	var errMessage="";
	var frm=document.getElementById("myForm");
	if (debug) {
		debugger;
	}
	if (inputType=="" || inputType==null) {
		alert("Input Fatal Error.  Please check the inputs calling functions");
	} else {
		var inputProper = objName.charAt(0).toUpperCase()+objName.substr(1, objName.length);
		switch (objName) {
			case "email":
				// validate email
				if (frm.email.value=="" || frm.email.value==null) { 
					errMessage="You must enter a your Email address!";
					formError=true;
				} else {
					var emailText = document.getElementById(inputType).value; // get email text
					var atpos = emailText.indexOf("@");
					var dotpos = emailText.lastIndexOf(".");
					if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=emailText.length) {
						errMessage="Not a valid Email address! Please enter a valid Email address!";
						formError=true;
					}
				}
				break;

			case "name":
					// validate the name
				if (frm.name.value=="" || frm.name.value==null) { 
					errMessage="You must enter your name!"; // error message if no name entered
					formError=true;
					frm.name.focus();
				} else {
					var nameText = document.getElementById(inputType).value; // get name text
					var atpos = nameText.indexOf(" ");
					var dotpos = nameText.lastIndexOf(" ");
					//alert("atpos: "+atpos+" dotpos: "+dotpos);
					if (atpos< 0) {
						errMessage="Please enter your first and last name!"; // error message
						formError=true;
					} 
				}
				break;
			
			default:
				var inputValue=document.getElementById(inputType).value;
				if (inputValue=="" || inputValue==null || inputValue=="Select") {
					if (inputValue=="Select") {
						errMessage="You must choose one of the "+inputType+"s!";
					} else {
						errMessage="You must enter your "+inputType+"!";
					}
					formError=true;
				}
		}
		if (formError) {
			document.getElementById("err"+inputProper).innerHTML=errMessage; // error message
			document.getElementById("errButton").innerHTML="Your form has errors!  Please correct before submitting!";
		} else {
			document.getElementById("err"+inputProper).innerHTML="";
			document.getElementById("errButton").innerHTML="";			
		}
	}
	return formError;
}

function genMessage() {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var debug=true;
	var frm=document.getElementById("myForm");
	var formError=false;
	var typeValue=["name", "subject", "email", "message"];
	var typeError=[];
	for (var x=0; x<=3; x++) {
		typeError[typeValue[x]]=checkName(document.getElementById(typeValue[x]).value, typeValue[x]);
	}
	for (var x=0; x<=3; x++) {
		if (typeError[typeValue[x]]=="Empty") {
			document.getElementById("err"+(typeValue[x].charAt(0).toUpperCase()+typeValue[x].substr(1, typeValue[x].length))).innerHTML="You must enter your "+typeValue[x]+"!";
			formError=true;
		}
		if (typeError[typeValue[x]]=="Full Name") {
			document.getElementById("err"+(typeValue[x].charAt(0).toUpperCase()+typeValue[x].substr(1, typeValue[x].length))).innerHTML="You must enter your first and last "+typeValue[x]+"!";
			formError=true;
		}
		if (typeError[typeValue[x]]=="Not Email") {
			document.getElementById("err"+(typeValue[x].charAt(0).toUpperCase()+typeValue[x].substr(1, typeValue[x].length))).innerHTML="Not a valid "+typeValue[x]+"! You must enter a valid "+typeValue[x]+"!";
			formError=true;
		}
		if (typeError[typeValue[x]]=="Select") {
			document.getElementById("err"+(typeValue[x].charAt(0).toUpperCase()+typeValue[x].substr(1, typeValue[x].length))).innerHTML="You must select a "+typeValue[x]+"!";
			formError=true;
		}
	}
	if (!formError) {
		var txtName = frm.name.value;
		var txtSubject = frm.subject.value;
		var txtEmail = frm.email.value;
		var txtPhone = frm.phone.value;
		var txtMessage = frm.message.value;
		frm.name.style.display="none";
		frm.subject.style.display="none";
		frm.email.style.display="none";
		frm.phone.style.display="none";
		frm.message.style.display="none";
		var genName=genPass(txtName);
		var genSubject=genPass(txtSubject);
		var genEmail=genPass(txtEmail);
		if (txtPhone==""){
			txtPhone="Empty"
			var genPhone=genPass(txtPhone);
		} else {
			var genPhone=genPass(txtPhone);
		}
		var genMessage=genPass(txtMessage);
		frm.name.value=genName+"Subject"+genSubject;
		frm.subject.value=genSubject;
		frm.email.value=genEmail;
		frm.phone.value=genPhone;
		frm.message.value=genMessage;
		return true;
		
	} else {
		return false;
	}
}

function genOldPass(txtValue) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var usrName = "";
	var pswrd = "";
	var dkeyLength=0;
	var dkey = Math.random();
	if (dkey < .5) {
		dkey=Math.floor(dkey*10);
	} else {
		dkey=Math.ceil(dkey*10);
	}
	while (dkey==0 || dkey==2 || dkey==10 || dkey==1) { // dkey cannot be 0, 1 or 10
		dkey = Math.random();
		if (dkey < .5) {
			dkey=Math.floor(dkey*10);
		} else {
			dkey=Math.ceil(dkey*10);
		}
	}
	dkey*=7;
	dkeyLength=(dkey.toString()).length;
	var newPassword = dkeyLength.toString();
	newPassword+=dkey.toString();
	dkey/=7;
	var i=0;
	var nChar = 0;
	var tmpPswrd = "";
	var tmpLength = 0;
	pswrd = txtValue;
	for (i=0; i < pswrd.length; i++) {
		nChar = pswrd.charCodeAt(i)*dkey;
		tmpPswrd = nChar.toString();
		tmpLength = tmpPswrd.length
		newPassword += tmpLength.toString() + tmpPswrd;
	}
	return newPassword;
}

function genPass(txtValue) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var usrName = "";
	var pswrd = "";
	var dkeyLength=0;
	var dkey = Math.random();
	var dsalt = 0.0;
	if (dkey < .5) {
		dkey=Math.floor(dkey*10);
	} else {
		dkey=Math.ceil(dkey*10);
	}
	while (dkey==0 || dkey==2 || dkey==10 || dkey==1) { // dkey cannot be 0, 1 or 10
		dkey = Math.random();
		if (dkey < .5) {
			dkey=Math.floor(dkey*10);
		} else {
			dkey=Math.ceil(dkey*10);
		}
	}
	dkey*=7;
	dkeyLength=(dkey.toString()).length;
	var newPassword = dkeyLength.toString();
	newPassword+=dkey.toString();
	
	dsalt = Math.random();
	if (dsalt < .5) {
		dsalt=Math.floor(dsalt*100);
	} else {
		dsalt=Math.ceil(dsalt*100);
	}
	while (dsalt > 19) {
		dsalt = Math.random();
		if (dsalt < .5) {
			dsalt=Math.floor(dsalt*100);
		} else {
			dsalt=Math.ceil(dsalt*100);
		}		
	}
	newPassword+=wicksaltitem(dsalt);
	
	dkey/=7;
	var i=0;
	var nChar = 0;
	var tmpPswrd = "";
	var tmpLength = 0;
	pswrd = txtValue;
	for (i=0; i < pswrd.length; i++) {
		nChar = pswrd.charCodeAt(i)*dkey;
		tmpPswrd = nChar.toString();
		tmpLength = tmpPswrd.length
		newPassword += tmpLength.toString() + tmpPswrd;
		
		if (i < pswrd.length-1) {
			dsalt = Math.random();
			if (dsalt < .5) {
				dsalt=Math.floor(dsalt*100);
			} else {
				dsalt=Math.ceil(dsalt*100);
			}
			while (dsalt > 19) {
				dsalt = Math.random();
				if (dsalt < .5) {
					dsalt=Math.floor(dsalt*100);
				} else {
					dsalt=Math.ceil(dsalt*100);
				}		
			}
			newPassword+=wicksaltitem(dsalt);
		} 
		
	}
	return newPassword;
}

function wicksaltitem(num) {
	// Copyright 2015 2633756393639094102638733972328836933288366639094103541035399941008 All Right Reserved
	var wsi = [];
	wsi[0]  = "f762qC7ZLWCM74rW";
	wsi[1]  = "876WvUNpk8Y8TF4V";
	wsi[2]  = "W3b67ce8FW3sv9Pp";
	wsi[3]  = "9oEq929MEkPfD26t";
	wsi[4]  = "9642wzn2DPVQ2huy";
	wsi[5]  = "zUL38p8dbt39nX3K";
	wsi[6]  = "9W8BX7ZPt7Y26qEH";
	wsi[7]  = "pLJ76G34gab97CpW";
	wsi[8]  = "9R7Y8s3yRZx47LJe";
	wsi[9]  = "u4aD3h7X49wYB7JK";
	wsi[10] = "NqZq892CVG74Pg3J";
	wsi[11] = "D2bhQ4oQR9r696mg";
	wsi[12] = "73vi689xjN9PvfAR";
	wsi[13] = "68PRM87WLZmGd82f";
	wsi[14] = "cLgw8nw29Hn632jE";
	wsi[15] = "26W6GPbzj43b8aKC";
	wsi[16] = "7u693Fe4F7aQPdip";
	wsi[17] = "9v2ib2s6f38RNsUq";
	wsi[18] = "xd4a783kVB4bA3Md";
	wsi[19] = "4s34A9ZTno6HDF8x";
	return wsi[num];
//263876WvUNpk8Y8TF4V3756876WvUNpk8Y8TF4V39369R7Y8s3yRZx47LJe39097u693Fe4F7aQPdip410269R7Y8s3yRZx47LJe3873W3b67ce8FW3sv9Pp39724s34A9ZTno6HDF8x32889W8BX7ZPt7Y26qEH3666zUL38p8dbt39nX3K39099v2ib2s6f38RNsUq4103526W6GPbzj43b8aKC410359W8BX7ZPt7Y26qEH39999oEq929MEkPfD26t41008W3b67ce8FW3sv9Pp


}

function checkName(txtValue, txtWhere) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var debug=false;
	if (debug) {debugger;}
	var formError="";
	if (txtValue=="" || txtValue==null) { 
		formError="Empty";
	} else {
		if (txtWhere=="name") {
			var nameText = txtValue // get name text
			var atpos = nameText.indexOf(" ");
			if (atpos< 0) {
				formError="Full Name";
			}
		} else if (txtWhere=="email") {
			if (txtValue=="" || txtValue==null) { 
				formError="Empty";
			} else {
				var emailText = txtValue; // get email text
				var atpos = emailText.indexOf("@");
				var dotpos = emailText.lastIndexOf(".");
				if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=emailText.length) {
					formError="Not Email";
				}
			}
		} else {
			if (txtValue=="" || txtValue==null || txtValue=="Select") {
				if (txtValue=="Select") {
					formError="Select";
				} else {
					formError="Empty";
				}
			}
		}

	}
	return formError;
}

function formSend() {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	return genMessage();
}

function goGetit() {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var user=document.getElementById("user").value;
	var pass=document.getElementById("pass").value;
	var logOnOk=userlogOn(user, pass);
	if (!logOnOk) {
		alert("You are not able to continue!");
	} else {
		document.getElementById("user").style.display="none";
		document.getElementById("pass").style.display="none";
		document.getElementById("btn").style.display="none";
		document.getElementById("uname").style.display="none";
		document.getElementById("upass").style.display="none";
		document.getElementById("mainbody").style.display="block";
		document.getElementById("btnReset").style.display="inline";
		document.getElementById("btnInfo").style.display="inline";

	}
}

function userlogOn(user, pass) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var m_user="24968PRM87WLZmGd82f35887u693Fe4F7aQPdip35189R7Y8s3yRZx47LJe370773vi689xjN9PvfAR3805xd4a783kVB4bA3Md3805zUL38p8dbt39nX3K377768PRM87WLZmGd82f3784"; //user name
	var m_pass="256u4aD3h7X49wYB7JK3696zUL38p8dbt39nX3K38329oEq929MEkPfD26t377626W6GPbzj43b8aKC392826W6GPbzj43b8aKC325673vi689xjN9PvfAR3840876WvUNpk8Y8TF4V3920u4aD3h7X49wYB7JK3256D2bhQ4oQR9r696mg3928u4aD3h7X49wYB7JK38327u693Fe4F7aQPdip3840W3b67ce8FW3sv9Pp3920"; // user password
	var key = (parseInt(m_user.substr(1, parseInt(m_user.charAt(0)))))/7;
	var newUser=getLogPass(key, user); // encrypt user name
	var key = (parseInt(m_pass.substr(1, parseInt(m_pass.charAt(0)))))/7;
	var newPass=getLogPass(key, pass); // encrypt user password
	var validUser = getPass(m_user);
	var validPass = getPass(m_pass);
	var compareUser = getPass(newUser);
	var comparePass = getPass(newPass);
	var logOnOk=true;

	if (validUser == compareUser && validPass == comparePass) {
		return logOnOk;
	} else {
		logOnOk=false;
		return logOnOk;
	}

	/*
	if (m_user==newUser) {
		if (m_pass==newPass) {
			return logOnOk;
		} else {
			logOnOk=false;
			return logOnOk;
		}
	} else {
		logOnOk=false;
		return logOnOk;
	} 
	*/

}

function getLogPass(key, pass) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var pswrd = "";
	var dkeyLength=0;
	var dkey = key;
	var dsalt = 0.0;
	dkey*=7;

	dkeyLength=(dkey.toString()).length;
	var newPassword = dkeyLength.toString();
	newPassword+=dkey.toString();
	dsalt = Math.random();
	if (dsalt < .5) {
		dsalt=Math.floor(dsalt*100);
	} else {
		dsalt=Math.ceil(dsalt*100);
	}
	while (dsalt > 19) {
		dsalt = Math.random();
		if (dsalt < .5) {
			dsalt=Math.floor(dsalt*100);
		} else {
			dsalt=Math.ceil(dsalt*100);
		}		
	}
	newPassword+=wicksaltitem(dsalt);
	dkey/=7;
	var i=0;
	var nChar = 0;
	var tmpPswrd = "";
	var tmpLength = 0;
	pswrd = pass;
	for (i=0; i < pswrd.length; i++) {
		nChar = pswrd.charCodeAt(i)*dkey;
		tmpPswrd = nChar.toString();
		tmpLength = tmpPswrd.length
		newPassword += tmpLength.toString() + tmpPswrd;
		dsalt = Math.random();
		if (dsalt < .5) {
			dsalt=Math.floor(dsalt*100);
		} else {
			dsalt=Math.ceil(dsalt*100);
		}
		while (dsalt > 19) {
			dsalt = Math.random();
			if (dsalt < .5) {
				dsalt=Math.floor(dsalt*100);
			} else {
				dsalt=Math.ceil(dsalt*100);
			}		
		}
		newPassword+=wicksaltitem(dsalt);
	}
	return newPassword;

}

function initMenu() {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	document.getElementById("mainbody").style.display="none";
	document.getElementById("btnReset").style.display="none";
	document.getElementById("btnInfo").style.display="none";
	document.getElementById("user").value="";
	document.getElementById("pass").value="";
	document.getElementById("user").focus();

}

function genInfo() {
	// copyright 2015 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var pass = document.getElementById("userPswrd").value;
	if (pass == "" || pass==null) {
		document.getElementById("passwrd").innerHTML="Your entry was blank";
		document.getElementById("passwrd").style.color="red";
		return;
	} else {
		var newPass=genPass(pass);
		document.getElementById("passwrd").innerHTML=newPass;
		document.getElementById("passwrd").style.color="#DBDBDB";
	}
}

function genOldInfo() {
	// copyright 2015 2283336341634043456338834323128329634043460346034443448 all rights reserved
	var pass = document.getElementById("olduserPswrd").value;
	if (pass == "" || pass==null) {
		document.getElementById("passwrd").innerHTML="Your entry was blank";
		document.getElementById("passwrd").style.color="red";
		return;
	} else {
		var newPass=genOldPass(pass);
		document.getElementById("passwrd").innerHTML=newPass;
		document.getElementById("passwrd").style.color="#DBDBDB";
	}
}

function goDisplay(x, y) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	if (y=="" || y==null) {
		alert("A fatal error occurred. No valid parameters for con "+x.substr(1, x.length)+"!");
		return;
	} else {
		var num = x.substr(1, x.length);
		var key = x.charAt(0);
		var decrypt="";
		if (document.getElementById("get"+num).value==("Get "+num)) {
			if (key=="U") {
				decrypt=getPass(y);
				document.getElementById("txtUser"+num).value=decrypt;
				document.getElementById("get"+num).value="Ret "+num;
				return;
			}
		}
		if (document.getElementById("con"+num).value==("Con "+num)) {
			if (key=="P") {
				decrypt=getPass(y);
				document.getElementById("txtPass"+num).value=decrypt;
				document.getElementById("con"+num).value="Pol "+num;
				return;
			}
		}
		if (document.getElementById("get"+num).value==("Ret "+num)) {
			if (key=="U") {
				document.getElementById("txtUser"+num).value="";
				document.getElementById("get"+num).value="Get "+num;
				return;
			}
		}
		if (document.getElementById("con"+num).value==("Pol "+num)) {
			if (key=="P") {
				document.getElementById("txtPass"+num).value="";
				document.getElementById("con"+num).value="Con "+num;
				return
			}
		}
	}
}

function initReset() {
	// copyright 2017 2289W8BX7ZPt7Y26qEH3336pLJ76G34gab97CpW3416876WvUNpk8Y8TF4V34049642wzn2DPVQ2huy3456pLJ76G34gab97CpW3388W3b67ce8FW3sv9Pp343226W6GPbzj43b8aKC3128NqZq892CVG74Pg3J32969oEq929MEkPfD26t34049W8BX7ZPt7Y26qEH3460u4aD3h7X49wYB7JK3460zUL38p8dbt39nX3K34449oEq929MEkPfD26t3448 all rights reserved
	document.getElementById("user").style.display="none";
	document.getElementById("pass").style.display="none";
	document.getElementById("btn").style.display="none";
	document.getElementById("uname").style.display="none";
	document.getElementById("upass").style.display="none";
	document.getElementById("mainbody").style.display="block";
	document.getElementById("btnReset").style.display="inline";
	document.getElementById("btnInfo").style.display="inline"


	var howMany= parseInt(document.getElementById("txtNumber").value);
	var frm = document.getElementById("openId");
	var counter = 0;
	var countOpen = 0;
	var countUsed = 0;
	var strOpenId = "";
	for (var x=1; x<=howMany;x++) {
		document.getElementById("txtUser"+x.toString()).value=" ";
		document.getElementById("txtPass"+x.toString()).value=" ";
		document.getElementById("get"+x.toString()).value="Get "+x.toString();
		document.getElementById("con"+x.toString()).value="Con "+x.toString();
		if (document.getElementById("hide"+x.toString()).value.trim() == "0") {
			strOpenId += x.toString() + "  ";
			countOpen++;
		}
		if (document.getElementById("hide"+x.toString()).value.trim() == "1") {
			countUsed++;
		}
		if (document.getElementById("hide"+x.toString()).value.trim() == "2") {
			counter++;
		}
	}
	strOpenId+=(" ::  Used: " + countUsed.toString() + "  ::  Unused: " + countOpen.toString() + "  ::  Number Left: " + counter.toString());
	frm.value=strOpenId;
}

function newFocus(where) {
	// copyright 2014 2283336341634043456338834323128329634043460346034443448 all rights reserved
	document.getElementById(where).focus();
}


