function showSessions(id) {
    fetch("getSession.php?event=" + id)
        .then(res => res.text()).then(res => {
            const tableDiv = document.getElementById("sessionTab");
            tableDiv.innerHTML = res;
        });
}

function fetchSessionsForEvent(id) {
    fetch("getSessionsForEvent.php?event=" + id)
        .then(res => res.text()).then(res => {
            document.getElementById("").outerHTML = res;
        })
}

$(document).on("change", "#addEventSel", function () {
    fetch("getSessionsForEvent.php?event=" + this.value)
        .then(res => res.text()).then(res => {
            // document.getElementById("addSessionSel").innerHTML = res;
            document.getElementById("addSessionSel").innerHTML = res;
        })
})

$(document).on("click", ".btnEditVenue", function () {
    const row = $(this).closest("tr");
    const id = parseInt(row.find('td:eq(0)').text());
    const name = row.find('td:eq(1)').text();
    const capacity = row.find('td:eq(2)').text();
    $("#venueEditId").val(id);
    $("#venueEditName").val(name);
    $("#venueEditCapacity").val(capacity);
})

$(document).on("click", ".btnEditUser", function () {
    const row = $(this).closest("tr");
    const id = parseInt(row.find('td:eq(0)').text());
    const name = row.find('td:eq(1)').text();
    const pass = row.find('td:eq(2)').text();
    const role = row.find('td:eq(3)').text();
    $("#userEditId").val(id);
    $("#userEditName").val(name);
    $("#userEditPassword").val(pass);
    $("#userEditRole").val(role);
})

$(document).on("click", ".btnEditEvent", function () {
    const row = $(this).closest("tr");
    const id = parseInt(row.find('td:eq(0)').text());
    const name = row.find('td:eq(1)').text();
    const start = row.find('td:eq(2)').text();
    const end = row.find('td:eq(3)').text();
    const allowed = row.find('td:eq(4)').text();
    const venue = row.find('td:eq(5)').text();
    $("#eventEditId").val(id);
    $("#eventEditName").val(name);
    $("#eventEditsd").val(start);
    $("#eventEdited").val(end);
    $("#eventEditAllowed").val(allowed);
    $("#eventEditVenue").val(1);
})

$(document).on("click", ".btnEditSession", function () {
    const row = $(this).closest("tr");
    const id = parseInt(row.find('td:eq(0)').text());
    const name = row.find('td:eq(1)').text();
    const start = row.find('td:eq(2)').text();
    const end = row.find('td:eq(3)').text();
    const allowed = row.find('td:eq(4)').text();
    const venue = row.find('td:eq(5)').text();
    $("#sessionEditId").val(id);
    $("#sessionEditName").val(name);
    $("#sessionEditsd").val(start);
    $("#sessionEdited").val(end);
    $("#sessionEditAllowed").val(allowed);
    $("#sessionEditVenue").val("");
})

function closeModal(id) {
    const modal = document.getElementById(id);
    var instance = M.Modal.getInstance(modal);
    instance.close();
}

$(document).ready(function () {
    $('.modal').modal();
});

