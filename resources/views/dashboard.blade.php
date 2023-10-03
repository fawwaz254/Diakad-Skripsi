@extends('app')

@section('meta')
    <!-- Meta -->
@endsection

@section('content')
    @include('topbar')
    @include('sidebar')
    <section class="content" id="content">
    </section>
    @include('footer')
@endsection

@section('js')
    <!-- <script>
        document.addEventListener("visibilitychange", () => {
            var original_title = location.hash;
            var target_url = original_title.replace('#', '');

            if(target_url.includes('e-learning-soal/list-ujian/test')){
                if (document.visibilityState === 'visible') {
                    if(localStorage.getItem('blok_user_next')){               
                        alert('Maaf akun anda akan di lock');
                        window.location.href = `${base_url}/${role_url}/user-locked`;
                    }else{
                        localStorage.setItem('blok_user_next', true);
                        alert('Kamu melakukan pelanggaran dengan membuka tab lain di browser, 1x lagi akun dapat terkena lock');
                    }
                }
            }
        });
    </script> -->
    <!-- Javascript -->
    <script>
        var dtButtonConfig = {
            buttons: [{
                extend: "pageLength",
                className: "bg-amber waves-effect"
            }, {
                extend: "print",
                text: "PDF",
                className: "bg-pink waves-effect",
                orientation: "landscape",
                exportOptions: {
                    columns: ":visible"
                },
                customize: function(e) {
                    $(e.document.body).css("font-size", "10pt"), $(e.document.body).find("table").addClass(
                        "compact").css("font-size", "inherit")
                }
            }, {
                extend: "excelHtml5",
                className: "bg-green waves-effect",
                exportOptions: {
                    columns: ":visible"
                }
            }, {
                extend: "colvis",
                text: "Kolom yang ditampilkan",
                className: "bg-blue waves-effect"
            }],
            dom: {
                button: {
                    className: "btn"
                }
            }
        };
        var dtLengButton = [
            [10, 25, 50, 100, -1],
            ['10', '25', '50', '100', 'All']
        ];
        var loadingHtml =
            '<div class="loading-html"><img src="{{ asset('js/loading_new.gif') }}" /><br><span><h4>Sedang memuat, mohon tunggu...</h4></span></div>';

        function initDtInputSearch(title) {
            return '<div class="form-group"><div class="form-line"><input class="form-control" type="text" placeholder="Search ' +
                title + '" onclick="stopPropagation(event);" /></div></div>';
        }

        function stopPropagation(evt) {
            if (evt.stopPropagation !== undefined) {
                evt.preventDefault();
                evt.stopPropagation();
            } else {
                evt.cancelBubble = true;
            }
        }

        $(document).ready(function() {
            var original_title = location.hash;
            var target_url = original_title.replace('#', '');
            if (target_url == '' || target_url == '/' || target_url == 'undefined') {
                loadContent('welcome');
            } else {
                loadContent(target_url);

                $('#modul-item-' + target_url.split('/')[0]).addClass('active');
                $('#modul-item-' + target_url.split('/')[0] + ' a').addClass('toggled');
                $('#modul-item-' + target_url.split('/')[0] + ' .ml-menu').css('display', 'block');
                $('#menu-item-' + target_url.split('/')[0] + '-' + target_url.split('/')[1]).addClass('active');
            }
        });

        $(document).on('click', 'a.target-link', function(e) {
            e.preventDefault();
            var item = $(this);
            var target_url = item.attr('href').split('#')[1];
            // loadURI(target_url);
            location.hash = target_url;
        });

        $(document).on('click', '.modul-item', function(e) {
            e.preventDefault();
            if ($(this).hasClass('active')) {

            } else {
                $('.modul-item').removeClass('active');
                $('.modul-item a').removeClass('toggled');
                $(this).addClass('active');
                $(this).find('a').addClass('toggled');
            }
        });

        $(document).on('click', '#leftsidebar .target-link', function(e) {
            e.preventDefault();
            if ($('body').width() < 1170) {
                $('body').removeClass('overlay-open');
                $('.overlay').fadeOut();
            }
        });

        // User's mouse is inside the page.
        // document.onmouseover = function() {
        //     window.innerDocClick = true;
        // }

        // User's mouse has left the page.
        // document.onmouseleave = function() {
        //     window.innerDocClick = false;
        // }

        window.onhashchange = function() {
            // if (window.innerDocClick) {
            //Your own in-page mechanism triggered the hash change
            // } else {
            //Browser back button was clicked
            var original_title = location.hash;
            var target_url = original_title.replace('#', '');
            if (target_url == '' || target_url == '/' || target_url == 'undefined') {
                loadContent('welcome');
            } else {
                loadContent(target_url);
            }
            // }
        }

        function loadURI(target_url, content) {
            var original_title = location.hash;
            var current_url = original_title.replace('#', '');

            if (current_url == target_url) {
                loadContent(target_url);
            } else {
                location.hash = target_url;
            }
        }

        function loadContent(target_url, content) {
            content = typeof content !== 'undefined' ? content : 'content';
            // NProgress.start();

            Pace.ignore(function() {
                $.ajax({
                    type: "GET",
                    @if (Auth::user()->must_change_password == 1)
                        url: base_url + '/' + role_url + '/' + 'must-change-password',
                    @else
                        url: base_url + '/' + role_url + '/' + target_url,
                    @endif
                    contentType: false,
                    beforeSend: function() {
                        $("#" + content).html(loadingHtml);
                    },
                    success: function(data) {
                        $("#" + content).html(data);
                        // NProgress.done();

                        $('.menu-item').removeClass('active');
                        $('#menu-item-' + target_url.split('/')[0] + '-' + target_url.split('/')[1])
                            .addClass('active');
                    },
                    error: function(xhr, status, error) {
                        // alert(xhr.responseText);
                    }
                });
            });
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
            }
        });

        function deleteAction(delete_url, element) {
            var item = $(element);
            $('button').attr('disabled', 'disabled');

            swal({
                title: "Are you sure?",
                text: "You won't be able to delete this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(result) {
                if (result) {
                    $.ajax({
                        type: "POST",
                        url: delete_url + '/' + item.attr('data-id'),
                        success: function(response) {
                            if (response.status == 200) {
                                vex.dialog.alert(response.message);
                            } else if (response.status == 201) {
                                vex.dialog.alert(response.message);
                                window.location.href = response.link;
                            } else if (response.status == 202) {
                                vex.dialog.alert(response.message);
                                loadURI(response.path);
                            } else if (response.status == 203) {
                                vex.dialog.alert(response.message);
                                primary_table.ajax.reload(null, false);
                            } else if (response.status == 300) {
                                vex.dialog.alert(response.message);
                            }
                        },
                        complete: function() {
                            $('button').removeAttr('disabled', 'disabled');
                        }
                    });
                } else {
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }

        $('#form-search').validate({
            rules: {
                'checkbox': {
                    required: true
                },
                'gender': {
                    required: true
                }
            },
            highlight: function(input) {
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function(input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function(error, element) {
                $(element).parents('.form-group').append(error);
            },
            submitHandler: function(form) {
                var path = 'search?' + $(form).serialize();
                location.hash = path;
            }
        });
    </script>
@endsection
