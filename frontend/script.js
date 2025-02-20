document.addEventListener("DOMContentLoaded", loadTasks);

const taskForm = document.getElementById("taskForm");
const taskLists = {
    pendiente: document.getElementById("taskListPendiente"),
    completada: document.getElementById("taskListCompletada"),
    aplazada: document.getElementById("taskListAplazada"),
    rechazada: document.getElementById("taskListRechazada")
};

taskForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const title = document.getElementById("taskTitle").value;

    const response = await fetch("http://localhost", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ title, status: "pendiente" })
    });

    if (response.ok) {
        document.getElementById("taskTitle").value = "";
        loadTasks();
    }
});

async function loadTasks() {
    const response = await fetch("http://localhost");
    const tasks = await response.json();
    
    Object.values(taskLists).forEach(list => list.innerHTML = "");
    
    tasks.forEach(task => {
        const li = document.createElement("li");
        li.setAttribute("draggable", true);
        li.dataset.id = task.id;
        li.dataset.status = task.status;
        li.classList.add("task-item");

        li.innerHTML = `
            ${task.title}
            <button class="delete" onclick="deleteTask(${task.id})">X</button>
        `;

        li.addEventListener("dragstart", () => li.classList.add("dragging"));
        li.addEventListener("dragend", () => li.classList.remove("dragging"));

        taskLists[task.status]?.appendChild(li);
    });

    addDragAndDrop();
}

async function deleteTask(id) {
    const response = await fetch("http://localhost", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    });

    if (response.ok) {
        loadTasks(); // Recargar lista
    } else {
        console.error("Error eliminando tarea");
    }
}

function addDragAndDrop() {
    Object.values(taskLists).forEach(list => {
        list.addEventListener("dragover", (e) => e.preventDefault());
        
        list.addEventListener("drop", async (e) => {
            const draggedTask = document.querySelector(".dragging");
            const newStatus = list.id.replace("taskList", "").toLowerCase();
            const taskId = draggedTask.dataset.id;

            await fetch("http://localhost", {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: taskId, status: newStatus })
            });

            loadTasks();
        });
    });
}
