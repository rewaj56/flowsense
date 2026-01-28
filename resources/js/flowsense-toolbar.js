document.addEventListener("DOMContentLoaded", () => {
    const toolbar = document.getElementById("flowsense-toolbar");
    if (!toolbar) return;

    // Collapse/expand toolbar
    const toggleBtn = document.getElementById("fs-toggle");
    if (toggleBtn) {
        toggleBtn.addEventListener("click", () => {
            toolbar.classList.toggle("collapsed");
            toggleBtn.innerText = toolbar.classList.contains("collapsed") ? "▼" : "▲";
        });
    }

    // Switch tabs
    document.querySelectorAll("#flowsense-toolbar .fs-tab").forEach((tab) => {
        tab.addEventListener("click", () => {
            document.querySelectorAll("#flowsense-toolbar .fs-tab").forEach((t) => t.classList.remove("active"));
            tab.classList.add("active");

            document.querySelectorAll("#flowsense-toolbar .fs-panel").forEach((p) => p.classList.remove("active"));
            const panel = document.getElementById("fs-panel-" + tab.dataset.tab);
            if (panel) panel.classList.add("active");
        });
    });

    // Collapsible view sections
    document.querySelectorAll(".fs-view-header").forEach((header) => {
        const icon = header.querySelector(".fs-view-toggle-icon");
        const content = header.nextElementSibling;
        if (!content) return;

        header.addEventListener("click", () => {
            const isOpen = content.style.display === "block";
            content.style.display = isOpen ? "none" : "block";
            if (icon) icon.classList.toggle("open", !isOpen);
        });
    });

    // Expandable SQL rows
    document.querySelectorAll(".fs-query-row").forEach((row) => {
        row.addEventListener("click", () => {
            const details = row.nextElementSibling;
            if (details) {
                details.style.display = details.style.display === "table-row" ? "none" : "table-row";
            }
        });
    });
});
