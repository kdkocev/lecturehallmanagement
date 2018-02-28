(function() {
  var startHour = window.startHour;
  var endHour = window.endHour;
  window.slot_time_length = 0.25; // in hours

  function dateString() {
    return window.date;
  }

  function twoNums(n) {
    if (n < 10) return "0" + n;
    return n;
  }

  window.slotId = 0;
  function createElement(id, top, height, title, content, startTime, endTime) {
    $.post(
      window.server + "/api/slot/render",
      {
        slot: JSON.stringify({
          id: id,
          top: top,
          height: height,
          title: title,
          content: content,
          startTime: startTime,
          endTime: endTime
        })
      },
      function(data) {
        $(".calendar").append(data);
      }
    );
  }

  // not used?
  function hourToPixels(hour) {
    return startHour + hour * (endHour - startHour);
  }

  function timeToPixels(date) {
    return (
      (date.getHours() + date.getMinutes() / 60 - startHour) /
      (endHour - startHour) *
      $(".calendar").height()
    );
  }
  function hoursToPixels(hours) {
    return (
      (hours - startHour) / (endHour - startHour) * $(".calendar").height()
    );
  }

  function heightToHours(height) {
    return height / $(".calendar").height() * (endHour - startHour) + startHour;
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
    var startTime = timeToPixels(new Date(slot.start_time));
    var height =
      timeToPixels(new Date(slot.end_time)) -
      timeToPixels(new Date(slot.start_time));
    createElement(
      "slot_" + slot.id,
      startTime,
      height,
      slot.title,
      slot.description,
      dateToHourAndMinutes(new Date(slot.start_time)),
      dateToHourAndMinutes(new Date(slot.end_time))
    );
  }

  if (window.isAdmin) {
    $(".calendar").on("click", ".show-notes-button", function(e) {
      var id = $(this)
        .parents(".slot")
        .attr("id")
        .substr(5);
      $.post(window.server + "/api/slot/renderNotes", { id: id }, function(
        data
      ) {
        $(".notes-modal-wrapper").html(data);
        $(".notes-modal").show();
      });
    });
    $(".notes-modal-close").click(function() {
      $(".notes-modal").hide();
    });
    $(".calendar").on("click", ".add-note-button", function(e) {
      var slotId = $(this)
        .parents(".slot")
        .attr("id")
        .substr(5);
      $(".add-note-modal").show();
      $(".add-note-modal")
        .find("input[name='slot_id']")
        .val(slotId);
    });
    $(".close-add-note-modal").click(function() {
      $(".add-note-modal").hide();
    });
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
        var startH = heightToHours(
          $(target).offset().top - $(".calendar").offset().top
        );
        var endH = heightToHours(
          $(target).offset().top - $(".calendar").offset().top + height
        );
        window.slot_time_length = endH - startH;

        var id = parseInt(
          $(target)
            .attr("id")
            .slice(5)
        );
        var startTime = dateString() + " " + minutesToTime(startH * 60) + ":00";
        var endTime = dateString() + " " + minutesToTime(endH * 60) + ":00";

        $.post(
          window.server + "/api/slot/update",
          { id: id, start_date: startTime, end_date: endTime },
          function(slot) {
            target.remove();
            renderSlotObject(slot);
          },
          "json"
        );
      });
    });
    $(".calendar").mousedown(function(e) {
      if (
        $(e.target).hasClass("slot") ||
        $(e.target).parents(".slot").length > 0
      )
        return;
      var slotLength = window.slot_time_length;

      var elementTop = e.pageY - $(".calendar").offset().top;

      var start = heightToHours(elementTop);
      var end = start + slotLength;
      var elementHeight = hoursToPixels(end - start);
      var dateS = dateString();
      var dateTimeString = dateS + " " + minutesToTime(start * 60) + ":00";
      var endDateString = dateS + " " + minutesToTime(end * 60) + ":00";

      $.post(
        window.server + "/api/slot/create",
        { slot: { start_date: dateTimeString, end_date: endDateString } },
        function(id) {
          createElement(
            "slot_" + id,
            elementTop,
            elementHeight,
            "",
            "",
            minutesToTime(start * 60),
            minutesToTime(end * 60)
          );
        }
      );
    });
    $(".calendar").on("click", ".remove-button", function() {
      var id = parseInt(
        $(this)
          .parents(".slot")
          .attr("id")
          .slice(5)
      );

      $(this)
        .parents(".slot")
        .remove();
      $.post(window.server + "/api/slot/delete", { id: id });
    });
    $(".calendar").on("click", ".toggle-lock-slot", function() {
      var id = parseInt(
        $(this)
          .parents(".slot")
          .attr("id")
          .slice(5)
      );

      $(this)
        .parents(".slot")
        .remove();
      $.post(
        window.server + "/api/slot/lock",
        { id: id },
        function(slot) {
          renderSlotObject(slot);
        },
        "json"
      );
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
      console.log(targetSlot.attr("id"));

      var startHour = minutesToTime(
        heightToHours(
          $(targetSlot).offset().top - $(".calendar").offset().top
        ) * 60
      );
      var endHour = minutesToTime(
        heightToHours(
          $(targetSlot).offset().top -
            $(".calendar").offset().top +
            $(targetSlot).height()
        ) * 60
      );

      $(".take-slot-modal").show();
      $(".take-slot-modal .slot-start-time").html(startHour);
      $(".take-slot-modal .slot-end-time").html(endHour);
      $(".take-slot-modal input[name='id']").val(
        targetSlot.attr("id").substr(5)
      );
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
    var id = parseInt(
      $(this)
        .parents(".slot")
        .attr("id")
        .slice(5)
    );

    $.post(window.server + "/api/slot/setfree", { id: id }, function() {
      window.location.reload();
    });
  });

  // initially loads the data
  $(document).ready(function() {
    $.post(
      window.server + "/api/slot/list",
      { date: window.date },
      function(slots) {
        slots.forEach(function(slot) {
          renderSlotObject(slot);
        });
      },
      "json"
    );
  });
})($);
