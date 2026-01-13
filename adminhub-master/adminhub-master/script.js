const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');


allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});

// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
});

// SEARCH FORM TOGGLE
const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
});

// RESPONSIVE BEHAVIOR
if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}

window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
});

// DARK MODE TOGGLE
const switchMode = document.getElementById('switch-mode');

if (switchMode) {
	switchMode.addEventListener('change', function () {
		if (this.checked) {
			document.body.classList.add('dark');
		} else {
			document.body.classList.remove('dark');
		}
	});
}


// MENU MANAGEMENT
document.addEventListener("DOMContentLoaded", loadMenus);

// Function to load and display all menus from the database
function loadMenus() {
  $.ajax({
    url: "fetch_menu.php",
    type: "GET",
    dataType: "json", // 🔴 THIS IS THE FIX
    success: function (menus) {
      let html = "";

      if (!menus || menus.length === 0) {
        $("#menuGrid").html("<p>No menu found</p>");
        return;
      }

      menus.forEach(menu => {
        html += `
          <div class="menu-card" onclick="editMenu(${menu.id})">
            <img src="img/default.png">
            <div class="menu-info">
              <h4>${menu.name}</h4>
              <p>RM ${menu.price}</p>
            </div>
          </div>
        `;
      });

      $("#menuGrid").html(html);
    },
    error: function (xhr) {
      console.error(xhr.responseText);
    }
  });
}


function openModal(mode, id = '', name = '', price = '') {
    $('#modalTitle').text(mode === 'add' ? 'Add Menu' : 'Edit Menu');
    $('#menuId').val(id);
    $('#menuName').val(name);
    $('#menuPrice').val(price);
    $('#deleteBtn').toggle(mode === 'edit'); 
    $('#menuModal').show();
}


function closeModal() {
    $('#menuModal').hide();
    $('#menuId').val('');
    $('#menuName').val('');
    $('#menuPrice').val('');
}


function saveMenu() {
  let id = $("#menuId").val();
  let url = id ? "update_menu.php" : "add_menu.php";

  $.ajax({
    url: url,
    type: "POST",
    data: {
      id: id,
      name: $("#menuName").val(),
      price: $("#menuPrice").val()
    },
    success: function (res) {
      if (res.trim() === "success") {
        closeModal();
        loadMenus();
      } else {
        alert(res);
      }
    },
    error: function (xhr) {
      alert(xhr.responseText);
    }
  });
}

function deleteMenu(id) {
    if (confirm('Are you sure you want to delete this menu?')) {
        $.ajax({
            url: 'deleteMenu.php',  
            type: 'POST',
            data: { id: id },
            success: function(response) {
                loadMenus();  
            },
            error: function() {
                alert('Error deleting menu.');
            }
        });
    }
}

$(document).ready(function () {
  loadMenus();
});
