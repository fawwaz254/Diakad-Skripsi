<style type="text/css">
    .folder:hover {
        transform: scale(1.2);
    }

    .folder {
        cursor: pointer;
    }

    .list-view {
        display: flex;
        flex-direction: column;
        margin: 0 -15px;
        padding-left: 15px;
        padding-right: 15px;
    }

    .list-view .folder:hover {
        transform: scale(1.1);
    }

    .list-view .folder {
        width: 100%;
        display: flex;
        align-items: center;
        margin: 5px 0;
    }

    .list-view .folder i {
        margin-right: 15px;
        font-size: 35px;
    }

    .list-view .folder span {
        font-size: 16px;
    }

    .view-toggle-btn {
        background: none;
        border: 1px solid #ddd;
        padding: 8px 12px;
        margin-left: 8px;
        transition: all 0.3s ease;
    }

    .view-toggle-btn.active {
        background: #00b0e4;
        color: white;
        border-color: #00b0e4;
    }
</style>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="block-header">
                <h2>
                    <a class="btn bg-blue waves-effect target-link"
                        href="{{url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}">
                        <i class="material-icons">backspace</i>
                        <span>Kembali</span>
                    </a>
                </h2>
            </div>

            <div class="card">
                <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Data File Kategori {{ $category->category_file_name }}</h2>
                    <div>
                        <button class="view-toggle-btn active" id="gridViewBtn" onclick="toggleGridView()">
                            <i class="material-icons">apps</i>
                        </button>
                        <button class="view-toggle-btn" id="listViewBtn" onclick="toggleListView()">
                            <i class="material-icons">list</i>
                        </button>
                    </div>
                </div>

                <div class="body">
                    <div id="folderContainer" class="row">
                        @foreach ($sub_category as $r)
                            <div class="col-md-3 folder-item">
                                <a href="{{ url(Request::segment(1) . '#manajemen-file/data-file/sub-category/' . $r->sub_category_file_id) }}" style="color: inherit;text-decoration: inherit;" class="folder-link">
                                    <div class="folder">
                                        <center>
                                            <i class="material-icons" style="color:#DAA520;font-size: 45px;">folder</i>
                                        </center>
                                        <center>
                                            <span>{{ $r->sub_category_file_name }}</span>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const gridViewBtn = document.getElementById("gridViewBtn");
    const listViewBtn = document.getElementById("listViewBtn");
    const folderContainer = document.getElementById("folderContainer");
    const savedView = localStorage.getItem('viewPreference') || 'grid';

    function applyView(view) {
        if (view === 'grid') {
            folderContainer.classList.remove('list-view');
            folderContainer.classList.add('grid-view');
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
        } else {
            folderContainer.classList.remove('grid-view');
            folderContainer.classList.add('list-view');
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
        }
    }

    function toggleGridView() {
        localStorage.setItem('viewPreference', 'grid');
        applyView('grid');
    }

    function toggleListView() {
        localStorage.setItem('viewPreference', 'list');
        applyView('list');
    }

    applyView(savedView);
</script>