{{-- Reusable Media Picker Modal --}}
<div id="mediaPickerOverlay" class="admin-modal-overlay">
    <div class="admin-modal" style="width:min(860px,95vw);max-height:85vh;">
        <div class="admin-modal-header">
            <h3>Media Library</h3>
            <button type="button" class="admin-modal-close" onclick="MediaPicker.close()">&times;</button>
        </div>
        <div class="admin-modal-body" style="padding:0;">
            {{-- Upload Tab --}}
            <div class="mp-tabs">
                <button type="button" class="mp-tab mp-tab-active" data-tab="library">Library</button>
                <button type="button" class="mp-tab" data-tab="upload">Upload New</button>
            </div>

            {{-- Upload Panel --}}
            <div class="mp-panel" id="mpUploadPanel" style="display:none;">
                <form id="mpUploadForm" class="mp-upload-form" enctype="multipart/form-data">
                    <div class="mp-dropzone" id="mpDropzone">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#c3c4c7" stroke-width="1.5">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        <p>Drop files here or <label for="mpFileInput" style="color:var(--wp-link);cursor:pointer;text-decoration:underline;">browse</label></p>
                        <input type="file" id="mpFileInput" name="file" accept="image/*" multiple style="display:none;">
                    </div>
                    <div class="mp-upload-fields" style="display:none;" id="mpUploadFields">
                        <div class="admin-form-grid-2" style="gap:10px;">
                            <div class="admin-form-row"><label>Title</label><input type="text" name="title" placeholder="Image title"></div>
                            <div class="admin-form-row"><label>Alt Text</label><input type="text" name="alt_text" placeholder="Alt text for accessibility"></div>
                            <div class="admin-form-row"><label>Folder</label><input type="text" name="folder" value="products" placeholder="products"></div>
                            <div class="admin-form-row"><label>Collection</label><input type="text" name="collection" value="images" placeholder="images"></div>
                        </div>
                        <div style="margin-top:12px;">
                            <button type="submit" class="admin-btn">Upload</button>
                            <span id="mpUploadStatus" style="margin-left:10px;font-size:12px;color:var(--wp-muted);"></span>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Library Panel --}}
            <div class="mp-panel" id="mpLibraryPanel">
                <div class="mp-library-toolbar">
                    <input type="search" id="mpSearch" placeholder="Search media..." style="flex:1;border:1px solid #8c8f94;border-radius:4px;padding:4px 8px;font-size:13px;">
                    <select id="mpFolderFilter" style="border:1px solid #8c8f94;border-radius:4px;padding:4px 8px;font-size:13px;">
                        <option value="">All Folders</option>
                    </select>
                </div>
                <div id="mpGrid" class="mp-grid">
                    <p style="text-align:center;color:var(--wp-muted);padding:40px;">Loading media...</p>
                </div>
            </div>
        </div>
        <div class="admin-modal-footer">
            <span id="mpSelectedInfo" style="flex:1;font-size:12px;color:var(--wp-muted);"></span>
            <button type="button" class="admin-btn-secondary" onclick="MediaPicker.close()">Cancel</button>
            <button type="button" class="admin-btn" id="mpInsertBtn" disabled>Select</button>
        </div>
    </div>
</div>