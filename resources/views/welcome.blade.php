<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Task Manager</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('bootstrap.min.css') }}" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <style>
            body {
              font-family: "Source Sans Pro", "Arial", sans-serif;
            }

            * {
              box-sizing: border-box;
            }

            .todo-list, .project-list {
              list-style-type: none;
              padding: 10px;
            }

            .done {
              text-decoration: line-through;
              color: #888;
            }

            .new-todo {
              width: 100%;
            }

            .trash-drop {
              border: 2px dashed #ccc !important;
              text-align: center;
              color: #e33;
            }

            .trash-drop:-moz-drag-over {
              border: 2px solid red;
            }

            .todo-item, .project-item {
              border: 1px solid #ccc;
              border-radius: 2px;
              padding: 14px 8px;
              margin-bottom: 3px;
              background-color: #fff;
              box-shadow: 1px 2px 2px #ccc;
              font-size: 22px;
            }

            .remove-item, .textRight {
              float: right;
              color: #a45;
              opacity: 0.5;
            }

            .todo-item:hover .remove-item {
              opacity: 1;
              font-size: 28px;
            }

            .colorBorder {border: 2px solid #007bff;}

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div id="app">
                    <div class="row">
                        <div class="col-md-9">
                            <ul class="todo-list">
                               <li @dragover.prevent @drop="dragFinish(-1, $event)" v-if="dragging > -1" class="trash-drop todo-item" v-bind:class="{drag: isDragging}">Delete</li>
                              
                              <li v-else>
                                <div class="row">
                                    <div class="col-md-3">
                                        <select id="selectedProject" class="new-todo todo-item colorBorder" style="padding: 17px 8px !important;" v-model="newTask.projectId">
                                            <option value="" selected>Anonimous</option>
                                            <option class="project-item" v-for="(project, i) in projects" v-key="i" :value="project.id + '___' + project.name">
                                                <span>@{{ project.name }}</span>
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-9">
                                        <input placeholder="Type new task and press enter" type="text" class="new-todo todo-item colorBorder" v-model="newTask.name" @keyup.enter="addItem">
                                    </div>
                                </div>
                                
                                
                              </li>

                              <li class="todo-item" v-for="(item, i) in todos" v-key="i" draggable="true" @dragstart="dragStart(i, $event)" @dragover.prevent @dragenter="dragEnter" @dragleave="dragLeave" @dragend="dragEnd" @drop="dragFinish(i, $event)">
                                  <span>@{{ item.name }}</span> 

                                  <div class="textRight">
                                    <small v-if="item.projectName">@{{ item.projectName }}</small>
                                    <small v-else>Anonimous</small>
                                    | <a href="#" @click="setEditRecord(item, i)">
                                        <svg class="bi bi-pencil-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M15.502 1.94a.5.5 0 010 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 01.707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 00-.121.196l-.805 2.414a.25.25 0 00.316.316l2.414-.805a.5.5 0 00.196-.12l6.813-6.814z"/>
                                          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 002.5 15h11a1.5 1.5 0 001.5-1.5v-6a.5.5 0 00-1 0v6a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-11a.5.5 0 01.5-.5H9a.5.5 0 000-1H2.5A1.5 1.5 0 001 2.5v11z" clip-rule="evenodd"/>
                                        </svg>
                                    </a>    
                                  </div>
                                  
                              </li>
                              
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul class="project-list">
                              <li class="project-item">
                                <h4 style="text-transform: uppercase; font-weight: bold;">Filter Tasks</h4>
                              </li>
                              <li class="project-item">
                                <a href="{{ route('tasks.dashboard') }}">All</a>
                              </li>
                              <li class="project-item">
                                <a href="{{ route('tasks.dashboard', ['project_id' => 'anonimous']) }}">Anonimous</a>
                              </li>
                              <li class="project-item" v-for="(project, i) in projects" v-key="i">
                                  <a :href="project.url">
                                    <span>@{{ project.name }}</span>

                                    <span v-if="project.taskCount"> [@{{ project.taskCount }} tasks]</span>
                                  </a> 
                              </li>
                            </ul>
                        </div>
                    </div>
                  
                </div>
            </div>
        </div> 


        <script src="{{ asset('vue.js') }}"></script>
        <script src="{{ asset('axios.min.js') }}"></script>
        <script>
            
            let taskManager = new Vue({
                el: "#app",
                data: {
                    editableRecordIndex: -1,
                    newTask: {
                        id: 0,
                        projectId: '',
                        projectName: '',
                        name: '',
                        displayOrder: 0,
                        priority: 0
                    },
                    todos: [
                        @foreach ($tasks as $task)
                            {
                                id: '{{ $task->id }}',
                                name: '{{ $task->name }}',
                                projectId: {{ $task->project_id ?? 0 }},
                                projectName: '{{ optional($task->project)->name }}',
                                displayOrder: {{ $task->display_order }},
                                priority: {{ $task->priority }},
                            },
                        @endforeach
                    ],
                    projects: [
                        @foreach ($projects as $project)
                            {
                                id: '{{ $project->id }}',
                                name: '{{ $project->name }}',
                                url: '{{ route('tasks.dashboard', ['project_id' => $project->id]) }}',
                                taskCount: {{ $project->tasks->count() }}
                            },
                        @endforeach
                    ],
                    dragging: -1
                },
                methods: {
                    addItem() {
                      let app = this;
                      if (!app.newTask.name) {
                        return;
                      }

                      if(app.newTask.projectId) {
                        selectedProjectInfo = app.newTask.projectId.split('___');
                        app.newTask.projectId = selectedProjectInfo[0];
                        app.newTask.projectName = selectedProjectInfo[1];
                      }

                      let newRecord = {
                        id: app.newTask.id,
                        name: app.newTask.name,
                        projectId: app.newTask.projectId,
                        projectName: app.newTask.projectName,
                        displayOrder: app.todos.length + 1,
                        priority: app.todos.length + 1
                      };

                      // Update current records in the view

                      // Save new record to the database
                      axios.post('/tasks', newRecord)
                      .then(function (response) {
                        console.log(response);
                        newRecord.id = response.data[0].id;
                        // console.log(response.data[1])

                        if(response.data[1] == 'new'){
                            // add new item to the task list in view
                            app.todos.push(newRecord);
                        
                        } else {
                            // update edited record in the view
                            if(app.editableRecordIndex != -1) {
                                console.log(newRecord)
                                console.log(app.todos[app.editableRecordIndex])
                                app.todos[app.editableRecordIndex].projectId = newRecord.projectId;
                                app.todos[app.editableRecordIndex].projectName = newRecord.projectName;
                                app.todos[app.editableRecordIndex].name = newRecord.name;

                            }

                            app.editableRecordIndex = -1;
                        }

                        app.updateProjectRecord();
                      })
                      .catch(function (error) {
                        console.log(error);
                      });
                      

                        // Clear Saved data
                        app.newTask = {
                            id: 0,
                            projectId: '',
                            projectName: '',
                            name: '',
                            displayOrder: 0,
                            priority: 0
                        }
                    },
                    setEditRecord(item, index) {
                        let app = this;

                        app.newTask = {
                            id: item.id,
                            projectId: (item.projectId != 0) ? item.projectId + '___' + item.projectName: '',
                            projectName: item.projectName,
                            name: item.name,
                            displayOrder: item.displayOrder,
                            priority: item.priority
                        }

                        app.editableRecordIndex = index;

                    },
                    removeItem(item) {
                      let app = this;
                      app.todos.splice(app.todos.indexOf(item), 1);

                      // console.log('item removed:')
                      // console.log(item)

                      axios({
                        method: 'delete',
                        url: 'tasks/' + item.id
                      })
                      .then(function (response) {
                        console.log(response);
                      })
                      .catch(function (error) {
                        console.log(error);
                      });
                    },
                    removeItemAt(index) {
                      let app = this;
                      let itemToRemove = app.todos[index];

                      

                      app.removeItem(itemToRemove)
                      // app.todos.splice(index, 1);
                    },
                    updateProjectRecord() {
                        let app = this;
                        axios.get('/projects')
                          .then(function (response) {
                            // handle success
                            console.log(response.data);
                            app.projects = response.data;
                          })
                          .catch(function (error) {
                            // handle error
                            console.log(error);
                          })
                          .then(function () {
                            // always executed
                          });
                    },
                    dragStart(which, ev) {
                      let app = this;

                      ev.dataTransfer.setData('Text', app.id);
                      ev.dataTransfer.dropEffect = 'move'
                      app.dragging = which;
                    },
                    dragEnter(ev) {
                      /* 
                          if (ev.clientY > ev.target.height / 2) {
                            ev.target.style.marginBottom = '10px'
                          } else {
                            ev.target.style.marginTop = '10px'
                          }
                      */
                    },
                    dragLeave(ev) {
                      /* 
                          ev.target.style.marginTop = '2px'
                          ev.target.style.marginBottom = '2px'
                      */
                    },
                    dragEnd(ev) {
                      let app = this;
                      
                      app.dragging = -1
                    },
                    dragFinish(to, ev) {
                      let app = this;

                      app.moveItem(app.dragging, to);
                      ev.target.style.marginTop = '2px'
                      ev.target.style.marginBottom = '2px'
                    },
                    moveItem(from, to) {
                      let app = this;

                      if (to === -1) {
                        app.removeItemAt(from);
                        
                        // Delete from database
                        // console.log('deletable: ')
                        // console.log(app.todos.splice(from, 1)[0])

                      } else {
                        app.todos.splice(to, 0, app.todos.splice(from, 1)[0]);
                      }
                    }
                },
                computed: {
                    isDragging() {
                      let app = this;

                      return app.dragging > -1
                    }

                },
                watch: {
                    todos: {
                      handler: function(todos) {
                        // todoStorage.save(todos);
                        //console.log(todos)
                        
                        // Update display_order after the sorting with dragging
                        axios({
                          method: 'patch',
                          url: '/tasks/task',
                          data: {
                            tasks: todos
                            }

                        }).then(function (response) {
                            console.log(response);
                          })
                          .catch(function (error) {
                            console.log(error);
                          });
                      },
                      deep: true
                    }
                }

            });
        </script>
    </body>
</html>
