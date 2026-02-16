const link = window.location;
const BASE_URL = $("#base-url").data("url");
const ADMIN_URL = $("#admin-url").data("url");

/* add active class on selected menu without nav-treeview class */
$("ul.nav-sidebar a").filter(function() {
    return this.href == link;
}).addClass("active");
/* add the menu-open class to the selected menu that has the nav-treeview class */
$("ul.nav-treeview a").filter(function() {
    return this.href == link;
}).parentsUntil(".nav-sidebar > .nav-treeview").addClass("menu-open").prev("a").addClass("active");
// create web loading animation
$(".preloader").fadeOut();


 // Dark Mode Switcher
var toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
var currentTheme = localStorage.getItem('theme');
var mainHeader = document.querySelector('.main-header');

if (currentTheme) {
    if (currentTheme === 'dark') {
        if (!document.body.classList.contains('dark-mode')) {
            document.body.classList.add("dark-mode");
        }
        if (mainHeader.classList.contains('navbar-light')) {
            mainHeader.classList.add('navbar-dark');
            mainHeader.classList.remove('navbar-light');
            mainHeader.classList.remove('navbar-white');
        }
        toggleSwitch.checked = true;
    }
}

function switchTheme(e) {
    if (e.target.checked) {
        if (!document.body.classList.contains('dark-mode')) {
            document.body.classList.add("dark-mode");
        }
        if (mainHeader.classList.contains('navbar-light')) {
            mainHeader.classList.add('navbar-dark');
            mainHeader.classList.remove('navbar-light');
            mainHeader.classList.remove('navbar-white');
        }
        localStorage.setItem('theme', 'dark');
    } else {
        if (document.body.classList.contains('dark-mode')) {
            document.body.classList.remove("dark-mode");
        }
        if (mainHeader.classList.contains('navbar-dark')) {
            mainHeader.classList.add('navbar-light');
            mainHeader.classList.remove('navbar-dark');
        }
        localStorage.setItem('theme', 'light');
    }
}

toggleSwitch.addEventListener('change', switchTheme, false);



// bootstrap WYSIHTML5 - text editor
/*
$('.textarea').summernote()

$('.daterange').daterangepicker({
    ranges: {
        Today: [moment(), moment()],
        Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate: moment()
}, function (start, end) {
    // eslint-disable-next-line no-alert
    alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
})

// The Calender
$('#calendar').datetimepicker({
    format: 'L',
    inline: true
})

// SLIMSCROLL FOR CHAT WIDGET
$('#chat-box').overlayScrollbars({
    height: '250px'
})*/
