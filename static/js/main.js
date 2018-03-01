(function() {
  var startHour = window.startHour;
  var endHour = window.endHour;
  window.slotTimeLength = 0.125; // in hours

  function twoNums(n) {
    if (n < 10) return "0" + n;
    return n;
  }
  function getSlotElementId(slot) {
    return parseInt(slot.attr("id").slice(5));
  }
  function pixelsToTimeInHours(px) {
    var ratio = px / $(".calendar").height();
    return ratio * (window.endHour - window.startHour) + window.startHour;
  }
  function hoursToMinutesAndHours(hours) {
    var m = Math.floor((hours * 60) % 60);
    var h = Math.floor(hours);
    if (h < 10) h = "0" + h;
    if (m < 10) m = "0" + m;
    return h + ":" + m;
  }
  function hoursToDateString(hours) {
    return window.date + " " + hoursToMinutesAndHours(hours) + ":00";
  }
  function url(path) {
    return window.server + path;
  }
  function getSlot(id, cb) {
    $.post(url("/api/slot/retreive"), { id: id }, cb, "json");
  }
  function renderSlotById(id) {
    getSlot(id, renderSlotObject);
  }

  function createElement(id, top, height, title, content, startTime, endTime) {
    var data = {
      slot: JSON.stringify({
        id: id,
        top: top,
        height: height,
        title: title,
        content: content,
        startTime: startTime,
        endTime: endTime
      })
    };
    $.post(url("/api/slot/render"), data, function(slot) {
      $(".calendar").append(slot);
    });
  }
  function timeToPixels(date) {
    return (
      (date.getHours() + date.getMinutes() / 60 - startHour) /
      (endHour - startHour) *
      $(".calendar").height()
    );
  }
  function minutesToTime(minutes) {
    return (
      twoNums(parseInt(minutes / 60)) + ":" + twoNums(parseInt(minutes % 60))
    );
  }
  function formatDate(date) {
    var fulldate =
      date.getFullYear() +
      "-" +
      twoNums(date.getMonth() + 1) +
      "-" +
      twoNums(date.getDate());
    var time =
      twoNums(date.getHours()) +
      ":" +
      twoNums(date.getMinutes()) +
      ":" +
      twoNums(date.getSeconds());
    return fulldate + " " + time;
  }
  function dateToHourAndMinutes(date) {
    return twoNums(date.getHours()) + ":" + twoNums(date.getMinutes());
  }
  function renderSlotObject(slot) {
    var top = timeToPixels(new Date(slot.start_time));
    var height =
      timeToPixels(new Date(slot.end_time)) -
      timeToPixels(new Date(slot.start_time));

    createElement(
      "slot_" + slot.id,
      top,
      height,
      slot.title,
      slot.description,
      dateToHourAndMinutes(new Date(slot.start_time)),
      dateToHourAndMinutes(new Date(slot.end_time))
    );
  }

  if (window.isAdmin) {
    $(".calendar").on("click", ".show-notes-button", function(e) {
      var id = getSlotElementId($(this).parents(".slot"));
      $.post(url("/api/slot/renderNotes"), { id: id }, function(data) {
        $(".notes-modal-wrapper").html(data);
        $(".notes-modal").show();
      });
    });
    $(".notes-modal-close").click(function() {
      $(".notes-modal").hide();
    });
    $(".calendar").on("click", ".add-note-button", function(e) {
      var id = getSlotElementId($(this).parents(".slot"));
      $(".add-note-modal").show();
      $(".add-note-modal")
        .find("input[name='slot_id']")
        .val(id);
    });
    $(".close-add-note-modal").click(function() {
      $(".add-note-modal").hide();
    });

    // TODO: remove this and replace it with two input fields
    $(".calendar").on("mousedown", ".resize-handle", function(e) {
      var startY = e.pageY;
      var target = $("#" + $(e.target).attr("ref"));
      var initialHeight = target.height();
      $(document).mousemove(function(e) {
        target.css("height", initialHeight + e.pageY - startY);
      });
      $(document).one("mouseup", function(e) {
        $(document).unbind("mousemove");
        var height = $(target).height();
        var slotTop = $(target).offset().top;
        var calendarOffset = $(".calendar").offset().top;

        var startH = pixelsToTimeInHours(slotTop - calendarOffset);
        var endH = pixelsToTimeInHours(slotTop - calendarOffset + height);

        window.slotTimeLength = endH - startH;

        var data = {
          id: getSlotElementId($(target)),
          start_date: hoursToDateString(startH),
          end_date: hoursToDateString(endH)
        };
        // Remove the element and render it again
        target.remove();
        $.post(url("/api/slot/update"), data, renderSlotObject, "json");
      });
    });

    $(".calendar").mousedown(function(e) {
      function isLocatedInASlot(target) {
        return target.hasClass("slot") || target.parents(".slot").length > 0;
      }
      if (!isLocatedInASlot($(e.target))) {
        createSlotByClicking(e.pageY);
      }
    });
    $(".calendar").on("click", ".remove-button", function() {
      var slot = $(this).parents(".slot");
      var id = getSlotElementId(slot);

      slot.remove();
      $.post(url("/api/slot/delete"), { id: id });
    });
    $(".calendar").on("click", ".toggle-lock-slot", function() {
      var slot = $(this).parents(".slot");
      var id = getSlotElementId(slot);

      slot.remove();
      $.post(url("/api/slot/lock"), { id: id }, renderSlotObject, "json");
    });
    $(".calendar").on("click", ".resize-mode", function() {
      $(this)
        .parents(".slot")
        .find(".resize-handle")
        .toggle();
    });
  } else {
    $(".calendar").on("click", ".take-slot-button", function() {
      var targetSlot = $(this).parents(".slot");
      var id = getSlotElementId(targetSlot);
      var slot = getSlot(id);

      $(".take-slot-modal").show();
      $(".take-slot-modal .slot-start-time").html(slot.start_time);
      $(".take-slot-modal .slot-end-time").html(slot.end_time);
      $(".take-slot-modal input[name='id']").val(id);
    });
    $(".take-slot-modal-close").click(function() {
      $(".take-slot-modal").hide();
    });
  }

  $(".calendar").on("click", ".settings-button", function() {
    $(this)
      .parent()
      .find(".settings-list")
      .toggle();
  });

  $(".calendar").on("click", ".delete-reservation", function() {
    var id = getSlotElementId($(this).parents(".slot"));

    $.post(url("/api/slot/setfree"), { id: id }, function() {
      window.location.reload();
    });
  });

  function createSlotByClicking(mouseY) {
    var offset = $(".calendar").offset().top;
    var startTimeSelected = pixelsToTimeInHours(mouseY - offset);
    var endTimeSelected = startTimeSelected + window.slotTimeLength;

    var slot = {
      start_date: hoursToDateString(startTimeSelected),
      end_date: hoursToDateString(endTimeSelected)
    };

    $.post(url("/api/slot/create"), { slot: slot }, renderSlotById);
  }

  // initially loads the data
  $(document).ready(function() {
    function forEachData(callback) {
      return function(elements) {
        elements.forEach(callback);
      };
    }
    var data = { date: window.date };
    $.post(url("/api/slot/list"), data, forEachData(renderSlotObject), "json");
  });
})($);
