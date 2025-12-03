document.addEventListener("DOMContentLoaded", () => {
    const userBtn = document.getElementById("user-menu-btn");
    const userMenu = document.getElementById("usermenu");

    if (!userBtn || !userMenu) return;

    // Toggle popover visibility
    userBtn.addEventListener("click", () => {
        const isOpen = userMenu.classList.contains("open");

        if (isOpen) {
            userMenu.classList.remove("open");
            userMenu.style.top = "";
            userMenu.style.left = "";
        } else {
            // Get button position
            const rect = userBtn.getBoundingClientRect();

            // Position the popover
            userMenu.style.position = "absolute";
            userMenu.style.top = `${rect.bottom + window.scrollY}px`;
            userMenu.style.left = `${rect.right - userMenu.offsetWidth + window.scrollX}px`; // bottom-end

            // Show popover
            userMenu.classList.add("open");
        }
    });

    // Optional: click outside to close
    document.addEventListener("click", (e) => {
        if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.remove("open");
            userMenu.style.top = "";
            userMenu.style.left = "";
        }
    });
});
