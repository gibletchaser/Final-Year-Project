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


// Function to load and display all menus from the database
function loadMenus() {
  $.ajax({
    url: "fetch_menu.php",
    type: "GET",
    dataType: "json",
    success: function (menus) {
      let html = "";

      if (!menus || menus.length === 0) {
        $("#menuGrid").html("<p>No menu found</p>");
        return;
      }

      menus.forEach(menu => {
        const imageSrc = menu.image ? menu.image : '';
        
        html += `
          <div class="menu-card">
            <img src="${imageSrc}" alt="${menu.name} image">

            <div class="menu-info">
              <h4>${menu.name}</h4>
              <p>RM ${menu.price}</p>

              <button class="edit-btn"
                data-id="${menu.id}"
                data-name="${menu.name}"
                data-price="${menu.price}">
                Edit
              </button>
            </div>
          </div>
        `;
      });

      $("#menuGrid").html(html);
    },
    error: function() {
      $("#menuGrid").html("<p>Error loading menus</p>");
    }
  });
}

function openModal(mode, menu = null) {
  $("#menuModal").css("display", "flex");

  if (mode === "add") {
    $("#modalTitle").text("Add Menu");
    $("#menuId").val("");
    $("#menuName").val("");
    $("#menuPrice").val("");
    $("#deleteBtn").hide();
  }

  if (mode === "edit" && menu) {
    $("#modalTitle").text("Edit Menu");
    $("#menuId").val(menu.id);
    $("#menuName").val(menu.name);
    $("#menuPrice").val(menu.price);
    $("#deleteBtn").show();
  }
}


function closeModal() {
  $("#menuModal").hide();
}

function saveMenu() {
  const id = $("#menuId").val();
  const url = id ? "update_menu.php" : "add_menu.php";

  $.post(url, {
    id,
    name: $("#menuName").val(),
    price: $("#menuPrice").val()
  }, function (res) {
    if (res.trim() === "success") {
      closeModal();
      loadMenus();
    } else {
      alert(res);
    }
  });
}

function deleteMenu() {
  const id = $("#menuId").val();

  if (!id) {
    alert("Invalid menu ID");
    return;
  }

  if (!confirm("Delete this menu?")) return;

  $.post("delete_menu.php", { id }, function (res) {
    if (res.trim() === "success") {
      closeModal();
      loadMenus();
    } else {
      alert(res);
    }
  });
}


$(document).ready(function () {
  $("#menuModal").hide();   // 🔥 FORCE CLOSED
  loadMenus();
});

$(document).on("click", ".edit-btn", function () {
  openModal("edit", {
    id: $(this).data("id"),
    name: $(this).data("name"),
    price: $(this).data("price")
  });
});

$("#menuModal").on("click", function (e) {
  if (e.target.id === "menuModal") {
    closeModal();
  }
});