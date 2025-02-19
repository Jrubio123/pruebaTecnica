const API_URL = 'http://localhost:8000/routes/tasks.php';

// Load tasks when page loads
document.addEventListener('DOMContentLoaded', loadTasks);

async function loadTasks() {
    try {
        const response = await fetch(API_URL);
        const tasks = await response.json();
        
        // Clear all columns
        document.querySelectorAll('.task-list').forEach(list => list.innerHTML = '');
        
        // Distribute tasks to their respective columns
        tasks.forEach(task => {
            const taskElement = createTaskElement(task);
            document.getElementById(task.status).appendChild(taskElement);
        });
    } catch (error) {
        console.error('Error loading tasks:', error);
    }
}

function createTaskElement(task) {
    const div = document.createElement('div');
    div.className = 'task';
    div.draggable = true;
    div.textContent = task.title;
    div.dataset.id = task.id;
    
    // Add drag events
    div.addEventListener('dragstart', handleDragStart);
    
    return div;
}

function handleDragStart(e) {
    e.dataTransfer.setData('text/plain', e.target.dataset.id);
}

// Add drag and drop events to columns
document.querySelectorAll('.task-list').forEach(column => {
    column.addEventListener('dragover', e => e.preventDefault());
    column.addEventListener('drop', handleDrop);
});

async function handleDrop(e) {
    e.preventDefault();
    const taskId = e.dataTransfer.getData('text/plain');
    const newStatus = e.target.closest('.task-list').id;
    
    try {
        const response = await fetch(API_URL, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: taskId,
                status: newStatus
            })
        });
        
        if (response.ok) {
            loadTasks();
        }
    } catch (error) {
        console.error('Error updating task:', error);
    }
}

function showAddTaskForm(status) {
    document.getElementById('addTaskModal').style.display = 'block';
    document.getElementById('addTaskModal').dataset.status = status;
}

function closeModal() {
    document.getElementById('addTaskModal').style.display = 'none';
}

async function addTask() {
    const title = document.getElementById('taskTitle').value;
    const status = document.getElementById('addTaskModal').dataset.status;
    
    if (!title) return;
    
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                title,
                status
            })
        });
        
        if (response.ok) {
            loadTasks();
            closeModal();
            document.getElementById('taskTitle').value = '';
        }
    } catch (error) {
        console.error('Error creating task:', error);
    }
}