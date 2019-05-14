// *****************************************************************************
// ** tooltips.js
// **
// ** Unobtrusive JavaScript function which will display a tool tip when called
// **
// ** Author: Stan Slaughter
// ** Date: 07/25/2008
// ** E-mail: Stan_Slaughter@Yahoo.Com
// ** Web: http://www.StanSight.Com/
// *****************************************************************************

  //////////////////////////////////////////////////////////////////////////////
  // tooltips - Find object which called tooltip then show the tooltip message
  //  @param msg - Message to display
  //  @param displayseconds - *Optional* Number of seconds to display the tool tip
  //  @return void
  //////////////////////////////////////////////////////////////////////////////
  function tooltips(msg,displayseconds) {

    // Get object reference to element which triggered this event
    var event = (this.event) ? this.event : tooltips.caller.arguments[0] || window.event;
    var obj = event.target ? event.target : event.srcElement;

    // If not passed in set the default value for the optional displayseconds parameter
    displayseconds = (typeof(displayseconds)=="undefined") ? 10 : displayseconds;

    // Show Tooltip
    showTooltips(obj,msg,displayseconds)
  }


  //////////////////////////////////////////////////////////////////////////////
  // showTooltips - Function to display a tool tip.
  //  @param obj - Object to display the tool tip for
  //  @param msg - Message to display
  //  @param displayseconds - Number of seconds to display the tool tip
  //  @return void
  //////////////////////////////////////////////////////////////////////////////

  var HideTipTimer = 0;
  function showTooltips(obj,msg,displayseconds) {

    // Figure out where the input field object is on the screen by adding
    // up all the offsets for all the containing parent objects
    var obj_pos = FindPosition(obj);
    var tip_left = obj_pos[0];
    var tip_top = obj_pos[1];

    // Add obj height to top position so tip box DIV will show up underneath "obj"
    tip_top += obj.offsetHeight;

    // Create DIV tag object through DOM to hold the "tip" message (if it does not already exist)
    if (document.getElementById("tip") == null) {

      // Create tip box DIV
      tipdiv = document.createElement("div");
      document.body.appendChild(tipdiv);
      tipdiv.setAttribute("id", "tip");

      // Format appearance of tip box DIV (Note: could have just used a CSS
      // class, but I wanted to make this idependent of external files)
      tipdiv.style.zIndex = "500";
      tipdiv.style.position = "absolute";
      tipdiv.style.fontFamily = "Arial";
      tipdiv.style.color = "black";
      tipdiv.style.backgroundColor = "#FFFFE6";
      tipdiv.style.border = "1px solid #7F7F7F";
      //tipdiv.style.padding = "0px";
      //tipdiv.style.textAlign = "left";
      tipdiv.style.cursor = "pointer";

      // Create Paragraph to hold message contents
      tipdivP = document.createElement("p");
      tipdiv.appendChild(tipdivP);
      tipdivP.setAttribute("id", "tipP");
      //tipdivP.style.margin = "8px";

      // Create shadow tip box
      shadowdiv = document.createElement("div");
      shadowdiv.setAttribute("id", "shadowTip");
      document.body.appendChild(shadowdiv);

      // Format appearance of shadow tip box (Note: could have just used a CSS
      // class, but I wanted to make this idependent of external files)
      //shadowdiv.style.zIndex = "499";
      shadowdiv.style.position = "absolute";
      shadowdiv.style.opacity = ".5";
      shadowdiv.style.backgroundColor = "#DFDFDF";
      //shadowdiv.style.border = "2px solid #EFEFEF";
      shadowdiv.innerHTML = "&nbsp;"

      // Allow a click on the "tip" div to hide the message.
      tipdiv.onclick = function (aEvent){
        document.getElementById("shadowTip").style.display="none";
        document.getElementById("tip").style.display="none";
      }

    } else {
      // Div Tag allready esists, clear out old timer then get reference to tip box
      clearTimeout(HideTipTimer);
      tipdiv = document.getElementById("tip");
      tipdiv.style.display="none"; // Hide any old message

      tipdivP = document.getElementById("tipP");
      tipdivP.innerHTML = "";

      shadowdiv = document.getElementById("shadowTip");
      shadowdiv.style.display="none";
    }

    // Roughly figure how how wide to make tip box DIV based on the number
    // of characters in the message. If max width is exceeded then just use max width
    var iFontSize = 15;
    var iCharWidth = iFontSize / 2; // Assume characters are half as wide as they are tall
    var iMaxWidth = 350;
    var iNumChars = msg.length;
    var tip_width = (iNumChars * iCharWidth + 6 > iMaxWidth ) ? iMaxWidth : iNumChars * iCharWidth + 6;

    // Set tip box width and font size
    tipdiv.style.width  = "absolute";
    //tipdiv.style.width  = tip_width + "px";
    tipdiv.style.fontSize = iFontSize + "px";

    // If message is not empty then postion and show tip box
    var display_type = "none";
    if (msg != "") {
      display_type = "block";

      // Assign the message to the tip box
      tipdivP.innerHTML = msg;
      var tipdivWidth = parseInt(tipdiv.style.width);

      // Keep tip box from running off right of screen
      var widthPad = 25; // scroll bar and shadow offset
      if (tip_left + widthPad + tipdivWidth > document.body.offsetWidth) {
        var diff = (tip_left + widthPad + tipdivWidth) - document.body.offsetWidth;
        tip_left -= diff;
      }

      // Position tip box DIV
      tipdiv.style.top = tip_top + "px";
      tipdiv.style.left = tip_left + "px";

      // Position shadow DIV
      shadowdiv.style.top = (2 + tip_top) + "px";
      shadowdiv.style.left = (4 + tip_left) + "px";

      // Show tip box and shadow
      tipdiv.style.display = display_type;
      shadowdiv.style.display = display_type;

      // Set shadows width and height based in tip box *after* message is assigned
      shadowdiv.style.width = "absolute";
      shadowdiv.style.height = "0px";
      //shadowdiv.style.height = (tipdiv.offsetHeight + 2) + "px";

      // If tip is being displayed then wait 'displayseconds' seconds then
      // hide the tip box DIV
      if (display_type == "block") {
        var hideTipCmd = 'document.getElementById("shadowTip").style.display="none";' +
                         'document.getElementById("tip").style.display="none";';
        displayseconds = displayseconds * 1000;
        HideTipTimer = setTimeout(hideTipCmd,displayseconds);
      }
    }

  }

  //////////////////////////////////////////////////////////////////////////////
  // FindPosition - find the Top and Left postion of an object on the page
  //  @param obj - object of element whose position needs to be found
  //  @return array - Array whoose first eleemnt is the left postion and whoose
  //                  second is the top position
  //////////////////////////////////////////////////////////////////////////////


  function FindPosition(obj) {
    // Figure out where the obj object is in the page by adding
    // up all the offsets for all the containing parent objects
    if (obj == null) return [0,0];

    // Assign the obj object to a temp variable
    tmpObj = obj;

    // Get the offsets for the current object
    var obj_left = tmpObj.offsetLeft;
    var obj_top = tmpObj.offsetTop;

    // If the current object has a parent (ie contained in a table, div, etc..)
    if (tmpObj.offsetParent) {

      // Loop through all the parents and add up their offsets
      // The while loop will end when no more parents exist and a null is returned
      while (tmpObj = tmpObj.offsetParent) {
      	obj_left += tmpObj.offsetLeft;
      	obj_top += tmpObj.offsetTop;
      }
    }
    return [obj_left , obj_top];
  }
