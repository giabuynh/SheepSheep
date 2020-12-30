// CHECK INPUT
function isEmpty(input) {
  if (input.value.trim() == '') return true;
  return false;
}

function checkRequire(id) {
  var message = document.getElementById(id+"-message").style.display = "none";
  var input = document.getElementById(id);
  if (id == "summary" || id == "content") input.style.border = "0.5px solid black";
  else input.style.border = "none";
  if (isEmpty(input))
  {
    input.style.border = "1px solid red";
    document.getElementById(id+"-message").innerHTML = "Required field.";
    document.getElementById(id+"-message").style.display = "flex";
    return false;
  }
  else return true;
}

function checkConfirm(pass, cf) {
  if (checkRequire(cf))
  {
    var password = document.getElementById(pass).value;
    var confirm = document.getElementById(cf);
    confirm.style.border = "none";
    document.getElementById(cf+"-message").style.display = "none";
    if (confirm.value != password)
    {
      confirm.style.border = "1px solid red";
      document.getElementById(cf+"-message").innerHTML = "Password are different.";
      document.getElementById(cf+"-message").style.display = "flex";
      return false;
    }
    return true;
  }
}

function checkForm(fields, idMessage) {
  for (var i = 0; i<fields.length; ++i)
  {
    var message;
    if ($(fields[i]).val() == null || $(fields[i]).val().trim() == '')
    {
      document.getElementById(fields[i].slice(1, fields[i].length)).focus();
      message = document.getElementById(idMessage);
      message.innerHTML = "Please fill all the required fields.";
      message.style.display = "flex";
      return false;
    }
    if ((fields[i]).slice(-5) == "email")
    {
      var email = document.getElementById(fields[i].slice(1, fields[i].length));
      if (!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email.value))
      {
        console.log("unvalid email");
        message = document.getElementById(fields[i].slice(1, fields[i].length)+'-message');
        message.innerHTML = "Unvalid email";
        message.style.display = "flex";
        return false;
      }
    }
    if ((fields[i]).slice(-5) == "uname")
    {
      var uname = document.getElementById(fields[i].slice(1, fields[i].length));
      if (!/^[a-zA-Z]\w+$/.test(uname.value)) // uname la chuoi ko chua khoang trang, bat dau bang chu
      {
        console.log("unvalid uname");
        message = document.getElementById(fields[i].slice(1, fields[i].length)+'-message');
        message.innerHTML = "Unvalid username";
        message.style.display = "flex";
        return false;
      }
    }
  }
  return true;
}
