<x-app-layout>

    <div class="py-5 py-md-8 bg-gradient">
        <div class="container">
            <div class="bg-white shadow-lg rounded-3 p-4 p-md-5 border">

                {{-- Toast Messages --}}
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center fade show">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h4 class="alert-heading d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Whoops! Please correct the following issues:
                        </h4>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Upload Form --}}
                <form method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data" onsubmit="return startUpload()" class="needs-validation" novalidate>
                    @csrf

                    {{-- Video Files Section --}}
                    <section class="pb-4 pb-md-5 mb-4 mb-md-5 border-bottom">
                        <div class="row g-4">
                            {{-- Thumbnail --}}
                            <div class="col-md-4">
                                <label for="thumbnail" class="form-label fw-bold text-center d-block mb-2">Thumbnail</label>
                                <div id="thumbnailDrop" class="dropzone border-2 border-dashed rounded-3 cursor-pointer position-relative overflow-hidden bg-light">
                                    <div id="thumbnailPlaceholder" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-3">
                                        <i class="bi bi-image fs-1 text-muted"></i>
                                        <p id="thumbnailDropText" class="fw-bold mb-1">Drag & Drop thumbnail</p>
                                        <p class="small text-muted">or click to upload (Image files)</p>
                                    </div>
                                    <div id="thumbnailPreviewContainer" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-none align-items-center justify-content-center">
                                        <img id="thumbnailPreview" class="w-100 h-100 object-fit-cover" alt="Thumbnail Preview">
                                        <button type="button" onclick="clearFile('thumbnail')" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle" title="Remove Thumbnail">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <input id="thumbnail" name="thumbnail" type="file" required class="d-none" accept="image/*" />
                                </div>
                                <div class="invalid-feedback">
                                    Please upload a thumbnail image.
                                </div>
                            </div>

                            {{-- Trailer --}}
                            <div class="col-md-4">
                                <label for="trailer" class="form-label fw-bold text-center d-block mb-2">Trailer (Optional)</label>
                                <div id="trailerDrop" class="dropzone border-2 border-dashed rounded-3 cursor-pointer position-relative overflow-hidden bg-light">
                                    <div id="trailerPlaceholder" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-3">
                                        <i class="bi bi-film fs-1 text-muted"></i>
                                        <p id="trailerDropText" class="fw-bold mb-1">Drag & Drop trailer</p>
                                        <p class="small text-muted">or click to upload (MP4, AVI, MOV)</p>
                                    </div>
                                    <div id="trailerPreviewContainer" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-none align-items-center justify-content-center">
                                        <video id="trailerPreview" controls class="w-100 h-100 object-fit-contain"></video>
                                        <button type="button" onclick="clearFile('trailer')" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle" title="Remove Trailer">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <input id="trailer" name="trailer" type="file" class="d-none" accept="video/mp4,video/avi,video/mov" />
                                </div>
                            </div>

                            {{-- Full Video --}}
                            <div class="col-md-4">
                                <label for="video_file" class="form-label fw-bold text-center d-block mb-2">Full Movie File</label>
                                <div id="videoDrop" class="dropzone border-2 border-dashed rounded-3 cursor-pointer position-relative overflow-hidden bg-light">
                                    <div id="videoPlaceholder" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-3">
                                        <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                        <p id="videoDropText" class="fw-bold mb-1">Drag & Drop movie file</p>
                                        <p class="small text-muted">or click to upload (MP4, AVI, MOV - Max 2GB)</p>
                                    </div>
                                    <div id="videoFilePreviewContainer" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-none align-items-center justify-content-center">
                                        <video id="videoFilePreview" controls class="w-100 h-100 object-fit-contain"></video>
                                        <button type="button" onclick="clearFile('video_file')" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle" title="Remove Video">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <input id="video_file" name="video_file" type="file" required class="d-none" accept="video/mp4,video/avi,video/mov" />
                                </div>
                                <div class="invalid-feedback">
                                    Please upload a video file.
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Movie Details Section --}}
                    <section class="pb-4 pb-md-5 mb-4 mb-md-5 border-bottom">
                        <h3 class="d-flex align-items-center mb-4">
                            <i class="bi bi-film text-success me-2"></i>
                            <span>Movie Details</span>
                        </h3>

                        {{-- Title --}}
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">Movie Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Enter movie title...">
                            <div class="invalid-feedback">
                                Please provide a movie title.
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Synopsis / Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Provide a detailed synopsis or description of the movie...">{{ old('description') }}</textarea>
                        </div>

                        <div class="row g-4">
                            {{-- Release Date --}}
                            <div class="col-md-6">
                                <label for="release_date" class="form-label fw-bold">Release Date</label>
                                <input type="date" class="form-control" id="release_date" name="release_date" value="{{ old('release_date') }}" required>
                                <div class="invalid-feedback">
                                    Please provide a release date.
                                </div>
                            </div>

                            {{-- Duration (in minutes) --}}
                            <div class="col-md-6">
                                <label for="duration" class="form-label fw-bold">Duration (minutes)</label>
                                <input type="number" class="form-control" id="duration" name="duration" value="{{ old('duration') }}" required min="1" placeholder="e.g., 120">
                                <div class="invalid-feedback">
                                    Please provide a valid duration.
                                </div>
                            </div>

                            {{-- Genre --}}
                            <div class="col-md-6">
                                <label for="genre" class="form-label fw-bold">Genre</label>
                                <select class="form-select" id="genre" name="genre">
                                    <option value="">Select a Genre</option>
                                    <option value="Action" {{ old('genre') == 'Action' ? 'selected' : '' }}>Action</option>
                                    <option value="Comedy" {{ old('genre') == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                                    <option value="Drama" {{ old('genre') == 'Drama' ? 'selected' : '' }}>Drama</option>
                                    <option value="Sci-Fi" {{ old('genre') == 'Sci-Fi' ? 'selected' : '' }}>Sci-Fi</option>
                                    <option value="Horror" {{ old('genre') == 'Horror' ? 'selected' : '' }}>Horror</option>
                                    <option value="Thriller" {{ old('genre') == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                                    <option value="Animation" {{ old('genre') == 'Animation' ? 'selected' : '' }}>Animation</option>
                                    <option value="Documentary" {{ old('genre') == 'Documentary' ? 'selected' : '' }}>Documentary</option>
                                    <option value="Romance" {{ old('genre') == 'Romance' ? 'selected' : '' }}>Romance</option>
                                    <option value="Fantasy" {{ old('genre') == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                                    <option value="Adventure" {{ old('genre') == 'Adventure' ? 'selected' : '' }}>Adventure</option>
                                    <option value="Musical" {{ old('genre') == 'Musical' ? 'selected' : '' }}>Musical</option>
                                    <option value="Mystery" {{ old('genre') == 'Mystery' ? 'selected' : '' }}>Mystery</option>
                                </select>
                            </div>

                            {{-- Language --}}
                            <div class="col-md-6">
                                <label for="language" class="form-label fw-bold">Language</label>
                                <select class="form-select" id="language" name="language">
                                    <option value="">Select Language</option>
                                    <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Hindi" {{ old('language') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                    <option value="Spanish" {{ old('language') == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                                    <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>French</option>
                                    <option value="German" {{ old('language') == 'German' ? 'selected' : '' }}>German</option>
                                    <option value="Japanese" {{ old('language') == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                                    <option value="Korean" {{ old('language') == 'Korean' ? 'selected' : '' }}>Korean</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- Cast Section --}}
                    <section class="pb-4 pb-md-5">
                        <h3 class="d-flex align-items-center mb-4">
                            <i class="bi bi-people-fill text-primary me-2"></i>
                            <span>Cast Members</span>
                        </h3>
                        <div id="cast-list" class="mb-3">
                            {{-- Initial cast input (Role then Name) --}}
                            <div class="row g-2 mb-2">
                                <div class="col-md-5">
                                    <input type="text" name="cast[0][role]" class="form-control" placeholder="Role (e.g., Actor, Director)">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="cast[0][name]" class="form-control" placeholder="Cast Member Name">
                                </div>
                                {{-- No remove button for the initial field --}}
                            </div>
                        </div>
                        <button type="button" onclick="addCast()" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add More Cast
                        </button>
                    </section>

                    {{-- Loader --}}
                    <div id="loader" class="text-center fw-bold text-primary my-4 d-none">
                        <div class="spinner-border me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span>Uploading... please wait</span>
                    </div>

                    {{-- Submit --}}
                    <div class="text-center mt-4">
                        <button id="submitBtn" type="submit" class="btn btn-primary btn-lg px-4 py-2 fw-bold">
                            <i class="bi bi-rocket me-2"></i>Upload Movie
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient {
            background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);
            min-height: 100vh;
        }
        .dropzone {
            min-height: 180px;
            transition: all 0.3s ease;
        }
        .dropzone:hover {
            border-color: #6366f1 !important;
        }
        .dropzone.active {
            border-color: #6366f1 !important;
            background-color: #eef2ff !important;
        }
        .dropzone.success {
            border-color: #10b981 !important;
            background-color: #ecfdf5 !important;
        }
    </style>

    <script>
        // Generic Drag & Drop Function with Preview
        function setupDragAndDrop(dropZoneId, fileInputId, dropTextId, previewElementId, previewContainerId, placeholderElementId) {
            const dropZone = document.getElementById(dropZoneId);
            const fileInput = document.getElementById(fileInputId);
            const dropText = document.getElementById(dropTextId);
            const previewElement = document.getElementById(previewElementId);
            const previewContainer = document.getElementById(previewContainerId);
            const placeholderElement = document.getElementById(placeholderElementId);

            // Trigger file input click when drop zone is clicked, but not on the preview container
            dropZone.addEventListener('click', (e) => {
                if (previewContainer.classList.contains('d-none') || !previewContainer.contains(e.target) || e.target.tagName === 'BUTTON' || e.target.tagName === 'I') {
                    fileInput.click();
                }
            });

            const updateDisplay = (file) => {
                if (file) {
                    const fileURL = URL.createObjectURL(file);
                    if (file.type.startsWith('image/')) {
                        previewElement.src = fileURL;
                    } else if (file.type.startsWith('video/')) {
                        previewElement.src = fileURL;
                        previewElement.load(); // Reload video to show new source
                    }

                    // Show preview, hide placeholder
                    previewContainer.classList.remove('d-none');
                    placeholderElement.classList.add('d-none');
                    dropZone.classList.add('success');
                    dropZone.classList.remove('bg-light');
                } else {
                    // Hide preview, show placeholder
                    previewElement.src = '';
                    if (previewElement.tagName === 'VIDEO') {
                        previewElement.pause();
                    }
                    previewContainer.classList.add('d-none');
                    placeholderElement.classList.remove('d-none');
                    dropZone.classList.remove('success');
                    dropZone.classList.add('bg-light');
                }
            };

            // Clear file function for the "Remove" button
            window.clearFile = (inputId) => {
                const input = document.getElementById(inputId);
                input.value = ''; // Clear the selected file
                updateDisplay(null); // Update display to show placeholder
            };

            dropZone.addEventListener('dragover', e => {
                e.preventDefault();
                dropZone.classList.add('active');
                // Adjust text for dragover
                if (dropZoneId === 'thumbnailDrop') {
                    dropText.textContent = 'Drop your thumbnail here!';
                } else if (dropZoneId === 'trailerDrop') {
                    dropText.textContent = 'Drop your trailer here!';
                } else if (dropZoneId === 'videoDrop') {
                    dropText.textContent = 'Drop your movie file here!';
                }
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('active');
                // Only revert if no file is currently selected
                if (!fileInput.files[0]) {
                    // Revert text to original
                    if (dropZoneId === 'thumbnailDrop') {
                        dropText.textContent = 'Drag & Drop thumbnail';
                    } else if (dropZoneId === 'trailerDrop') {
                        dropText.textContent = 'Drag & Drop trailer';
                    } else if (dropZoneId === 'videoDrop') {
                        dropText.textContent = 'Drag & Drop movie file';
                    }
                }
            });

            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                dropZone.classList.remove('active');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    updateDisplay(files[0]);
                }
            });

            fileInput.addEventListener('change', () => {
                updateDisplay(fileInput.files[0]);
            });

            // Initial check in case of old() values or pre-filled forms
            if (fileInput.files.length > 0) {
                updateDisplay(fileInput.files[0]);
            }
        }

        // Initialize Drag & Drop for all file inputs
        document.addEventListener('DOMContentLoaded', () => {
            setupDragAndDrop('thumbnailDrop', 'thumbnail', 'thumbnailDropText', 'thumbnailPreview', 'thumbnailPreviewContainer', 'thumbnailPlaceholder');
            setupDragAndDrop('trailerDrop', 'trailer', 'trailerDropText', 'trailerPreview', 'trailerPreviewContainer', 'trailerPlaceholder');
            setupDragAndDrop('videoDrop', 'video_file', 'videoDropText', 'videoFilePreview', 'videoFilePreviewContainer', 'videoPlaceholder');
        });

        // Add Cast Field with Role and Name
        let castIndex = 1; // Start from 1 as 0 is initial
        function addCast() {
            const castList = document.getElementById('cast-list');
            const newCastInputWrapper = document.createElement('div');
            newCastInputWrapper.className = 'row g-2 mb-2';

            // Role Input (first)
            const roleCol = document.createElement('div');
            roleCol.className = 'col-md-5';
            const roleInput = document.createElement('input');
            roleInput.type = 'text';
            roleInput.name = `cast[${castIndex}][role]`;
            roleInput.placeholder = 'Role (e.g., Actor, Director)';
            roleInput.className = 'form-control';
            roleCol.appendChild(roleInput);

            // Name Input (second)
            const nameCol = document.createElement('div');
            nameCol.className = 'col-md-5';
            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.name = `cast[${castIndex}][name]`;
            nameInput.placeholder = 'Cast Member Name';
            nameInput.className = 'form-control';
            nameCol.appendChild(nameInput);

            // Remove Button
            const buttonCol = document.createElement('div');
            buttonCol.className = 'col-md-2';
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-outline-danger btn-sm w-100';
            removeButton.innerHTML = '<i class="bi bi-trash"></i>';
            removeButton.onclick = () => newCastInputWrapper.remove();
            removeButton.title = "Remove Cast Member";
            buttonCol.appendChild(removeButton);

            newCastInputWrapper.appendChild(roleCol);
            newCastInputWrapper.appendChild(nameCol);
            newCastInputWrapper.appendChild(buttonCol);
            castList.appendChild(newCastInputWrapper);

            castIndex++; // Increment index for next cast member
        }

        // Form validation
        (function () {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Upload Loader
        function startUpload() {
            const form = document.querySelector('form.needs-validation');
            if (!form.checkValidity()) {
                return false;
            }

            document.getElementById('loader').classList.remove('d-none');
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Uploading...
            `;
            return true;
        }
    </script>
</x-app-layout>