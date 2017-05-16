<?php
if(isset($tpl))
    $t = $tpl;
else $t = 'admin.index';
?>
@extends($t)


@section('content')



{!! Form::open(array('action' => 'ItemController@store', 'class' => 'form', 'files'=>true)) !!}
<!--<input type="hidden" name="redirect_after_edit" value="" />-->
    
<div style="height: 5px;
    width: 443px;
    border-right: 5px solid #f00;
    position: absolute;
    top: 100px;" ></div>
<div class="row">
    <div class="col-md-12">
        @if(isset($obj->id))
            @foreach(\App\Item::first()->getFillableEdit() as $field => $type)
                <div class="form-group">
                    {!! Form::label($field) !!}
                    @if($type[0] == 'checkbox')
                    {!! Form::checkbox($field, 1,  $obj->$field=="1" ? true : false); !!}
                    @else
                    {!! Form::$type[0]($field, $obj->$field, 
                    array('', 
                    $type[1]=>$type[1],
                    'class'=>'form-control disabled', 
                    'placeholder'=>$field, false)) !!}
                    @endif
                </div>
            @endforeach
        @else
            @foreach(\App\Item::getFillableCreate() as $field => $type)
                <div class="form-group">
                    {!! Form::label($field) !!}
                    @if($type[0] == 'checkbox')
                        {!! Form::checkbox($field, 1,  $obj->$field=="1" ? true : false); !!}
                    @else
                        {!! Form::$type($field, null, 
                        array('', 
                        'class'=>'form-control', 
                        'placeholder'=>$field)) !!}
                    @endif
                </div>
            @endforeach
        @endif

        <div class="form-group">
            <div class="row">
                <div class="col-md-1">
                    {!! Form::button('Ok', 
                    array('class'=>'btn btn-primary submit-form')) !!}
                </div>
            </div>
        </div>
    </div>
</div>
       
<div class="row">
    <div class="col-md-12">
        <!---------------------------------------- Start Extend Description --------------------------------------------------->

        <h2>Dodatkowe opisy, wartości widoczne na stronie produktu <font style="color:#888;font-size:15px;">(może być html, te bez wpisanej nazwy nie zapiszą się)</font></h2>
        <div class="form-group ext-desc-list">
            @if(isset($ext_description) && !empty((array)$ext_description))
                <?php $i = 0; ?>
                @foreach($ext_description as $ed_k => $ed_v)
                    <div class="row ext-desc-list-panel" data-ext-desc-nr="{{$i}}">
                        <div class="col-md-12" data-number="{{$i}}">
                            <input class="form-control disabled ext-desc_name" placeholder="Nazwa w menu" data-number="{{$i}}" data-ext-desc-nr="0" type="text" value="{{ $ed_k }}">
                        </div>
                        <div class="col-md-12" data-number="{{$i}}">
                            <div class="tex-or-display-btn btn btn-primary" data-number="{{$i}}">D/S</div>
                            <textarea class="form-control disabled ext-desc_value hidden" placeholder="Wartość opcji" data-number="{{$i}}" data-ext-desc-nr="0" style="height:200px;">{!! $ed_v !!}</textarea>
                            <div class="col-md-8 ext-desc-panel" data-number="{{$i}}">{!! $ed_v !!}</div>
                            <!--Dropzone--> 
                            <article class="dropzone-ext-desc" data-number="{{$i}}">
                                <div id="holder" class="holder" data-number="{{$i}}">
                                </div> 
                                <p style="display:none" id="upload" class="hidden"><label>Drag & drop not supported, but you can still upload via this input field:<br><input name="item_image_ext_desc" type="file"></label></p>
                                <p style="display:none" id="filereader">File API & FileReader API not supported</p>
                                <p style="display:none" id="formdata">XHR2's FormData is not supported</p>
                                <p style="display:none" id="progress">XHR2's upload progress isn't supported</p>
                                <p style="display:none" style="display:none">Upload progress: <progress id="uploadprogress" min="0" max="100" value="0">0</progress></p>
                            </article>
                        </div>
                    </div>
                    <?php $i++; ?>
                @endforeach
            @else 
                <div class="row ext-desc-list-panel" data-ext-desc-nr="0">
                    <div class="col-md-12" data-number="0">
                        <input class="form-control disabled ext-desc_name" placeholder="Nazwa w menu" data-number="0" data-ext-desc-nr="0" type="text" value="">
                    </div>
                    <div class="col-md-12 ext-desc-value" data-number="0">
                        <textarea class="form-control disabled ext-desc_value" placeholder="Wartość opcji" data-number="0" data-ext-desc-nr="0" style="height:200px;"></textarea>
                        <article class="dropzone-ext-desc" data-number="0">
                            <div id="holder" class="holder" data-number="0">
                            </div> 
                            <p style="display:none" id="upload" class="hidden"><label>Drag & drop not supported, but you can still upload via this input field:<br><input name="item_image_ext_desc" type="file"></label></p>
                            <p style="display:none" id="filereader">File API & FileReader API not supported</p>
                            <p style="display:none" id="formdata">XHR2's FormData is not supported</p>
                            <p style="display:none" id="progress">XHR2's upload progress isn't supported</p>
                            <p style="display:none">Upload progress: <progress id="uploadprogress" min="0" max="100" value="0">0</progress></p>
                        </article>
                    </div>
                </div>
            @endif
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-2 pull-right">
                    <input class="btn btn-primary add-ext-desc" style="float: right;" type="button" value="Dodaj opcję opisu">
                </div>
            </div>
        </div>

        <!-------------------------------------- End Extend Description-------------------------------------->
    </div>
</div> 
        
<div class="row">
    <div class="col-md-6">
        <h1>Edit item</h1>
        {{-- @include('admin.errors') --}}

        @if(isset($obj->id))
        {!! Form::hidden('id', $obj->id) !!}
        @endif
        <div class="form-group">
            {!! Form::label('Category') !!}
            {!! Form::select('category_id[]', 
            \App\Category::getAllCategoryNamesWithIdes(true), 
            isset($obj->id) ? $obj->getCategoriesIds() : [],
            array('required', 
            'multiple' => 'multiple',
            'class'=>'form-control',
            'style'=>'height:300px', 
            'placeholder'=>'category_id[]')) !!}
        </div>
        <div class="form-group">
            {!! Form::label('Producent') !!}
            @if(isset($obj->producer->name))
            <p>{{ $obj->producer->name }}</p>
            @endif
        </div>
        <div class="form-group">
            {!! Form::label('Wysyłka') !!}
            {!! Form::select('shipment_id', 
            array_combine(\App\Shipment::active()->get()->pluck('id')->all(), \App\Shipment::active()->get()->pluck('description')->all()), 
            isset($obj->shipment_id) ? $obj->shipment_id : null,
            array('required', 
            'class'=>'form-control',
            'placeholder'=>'shipment_id[]')) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="col-md-2">
            <!--<input name="item_image" type='file' accept='image/*' onchange='openFile(event)'>-->
            {!! Form::file('item_image', array(
            'onchange' => 'openFile(event)',
            'accept' => 'image/gif, image/jpeg, image/png'
            )) !!}
        </div>
        <div class="col-md-10">
            <img id="item-image" class="upload-item" src="@if(isset($obj->image_path)) {{ url('image/' . $obj->image_path . '?w=300') }} @endif" />
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <!--Start Variants-->
        <div class="form-group variant-list">
            <h2>Warianty produktu</h2>
            @if(isset($variants) && $variants->count() > 0)

                <?php
                $i = $j = 0;
                ?>
                @foreach($variants as $v)
                    <div class="row variant-list-panel" data-variant-nr="{{$i}}">
                        <div class="col-md-1 col-md-offset-10" data-number="{{$j}}" style="padding: 0;">
                            <input class="form-control" name="variant_price" type="text" value="{{ $v->price }}" placeholder="Cena" data-variant-nr="{{$i}}">
                        </div>
                        <div class="col-md-1 " data-number="{{$j}}" style="padding: 0;">
                            <input class="form-control" name="variant_ean" type="text" value="{{ $v->ean }}" placeholder="Kod EAN" data-variant-nr="{{$i}}">
                        </div>
                        @foreach(json_decode($v->data) as $k=>$v)
                            <div class="col-md-3" data-number="{{$j}}">
                                <input class="form-control disabled option_name" placeholder="Nazwa opcji" data-number="{{$j}}" data-variant-nr="{{$i}}" type="text" value="{{$k}}">
                            </div>
                            <div class="col-md-8" data-number="{{$j}}">
                                <input class="form-control disabled option_value" placeholder="Wartość opcji" data-number="{{$j}}" data-variant-nr="{{$i}}" type="text" value="{{$v}}">
                            </div>
                            <div class="col-md-1" data-number="{{$j}}" style="padding: 0;">
                                <input class="btn btn-danger remove-variant-option" type="button" value="-" data-number="{{$j}}" data-variant-nr="{{$i}}">
                                <input class="btn btn-primary add-variant-option" type="button" value="+" data-number="{{$j}}" data-variant-nr="{{$i}}" style="margin-left: 18px;">
                            </div>
                            <?php   $j++;   ?>
                        @endforeach
                    </div>
                    <?php   $i++;   ?>
                @endforeach
            @else
                <div class="row variant-list-panel" data-variant-nr="0">
                    <div class="col-md-1 col-md-offset-10" data-number="0" style="padding: 0;">
                        <input class="form-control" name="variant_price" type="text" val="" placeholder="Cena" data-variant-nr="0">
                    </div>
                    <div class="col-md-1 " data-number="0" style="padding: 0;">
                        <input class="form-control" name="variant_ean" type="text" val="" placeholder="Kod EAN" data-variant-nr="0">
                    </div>

                    <div class="col-md-3" data-number="0">
                        <input class="form-control disabled option_name" placeholder="Nazwa opcji" data-number="0" data-variant-nr="0" type="text" value="">
                    </div>
                    <div class="col-md-8" data-number="0">
                        <input class="form-control disabled option_value" placeholder="Wartość opcji" data-number="0" data-variant-nr="0" type="text" value="">

                    </div>
                    <div class="col-md-0" data-number="0" style="padding: 0;">
                        <input class="btn btn-danger remove-variant-option" type="button" value="-" data-number="0" data-variant-nr="0">
                        <input class="btn btn-primary add-variant-option" type="button" value="+" data-number="0" data-variant-nr="0" style="margin-left: 18px;">
                    </div>
                </div>
            @endif
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-2 pull-right">
                    <input class="btn btn-primary add-variant" style="float: right;" type="button" value="Dodaj wariant produktu">
                </div>
            </div>
        </div>
    </div>
</div>

<!--End Variants-->
{!! Form::close() !!}


<script>
    $(document).ready(function () {
        $('.tex-or-display-btn').on('click', function(){
            var d_number = $(this).data('number');
            $('.ext-desc-panel[data-number="'+d_number+'"]').toggleClass('hidden');
            $('.ext-desc_value[data-number="'+d_number+'"]').toggleClass('hidden');
        });

        loadDropzone();
        // ======================================================== Start Drop zone ============================================================== //
        function loadDropzone(){
            var i=0;
            while(document.getElementsByClassName('holder').item(i)){
            var holder = document.getElementsByClassName('holder').item(i),
                    tests = {
                        filereader: typeof FileReader != 'undefined',
                        dnd: 'draggable' in document.createElement('span'),
                        formdata: !!window.FormData,
                        progress: "upload" in new XMLHttpRequest
                    },
            support = {
                filereader: document.getElementById('filereader'),
                formdata: document.getElementById('formdata'),
                progress: document.getElementById('progress')
            },
            acceptedTypes = {
                'image/png': true,
                'image/jpeg': true,
                'image/gif': true
            },
            progress = document.getElementById('uploadprogress'),
                    fileupload = document.getElementById('upload');



            //        "filereader formdata progress".split(' ').forEach(function (api) {
            //            if (tests[api] === false) {
            //                support[api].className = 'fail';
            //            } else {
            //                // FFS. I could have done el.hidden = true, but IE doesn't support
            //                // hidden, so I tried to create a polyfill that would extend the
            //                // Element.prototype, but then IE10 doesn't even give me access
            //                // to the Element object. Brilliant.
            //                support[api].className = 'hidden';
            //            }
            //        });

            function previewfile(file) {
                if (tests.filereader === true && acceptedTypes[file.type] === true) {
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        var image = new Image();
                        image.src = event.target.result;
                        image.width = 250; // a fake resize
                        holder.appendChild(image);
                    };

                    reader.readAsDataURL(file);
                } else {
                    holder.innerHTML += '<p>Uploaded ' + file.name + ' ' + (file.size ? (file.size / 1024 | 0) + 'K' : '');
                    console.log(file);
                }
            }

            function readfiles(files, data_number) {
                //debugger;
                var formData = tests.formdata ? new FormData() : null;
                for (var i = 0; i < files.length; i++) {
                    if (tests.formdata){
                        formData.append('file', files[i]);
                    }   
    //                previewfile(files[i]); // pokaz zaladowany obrazek w dropzonie
                }

                // now post a new XHR request
                if (tests.formdata) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/huj');
                    xhr.onload = function () {
                        progress.value = progress.innerHTML = 100;
                        var textAreaDescVal = document.getElementsByClassName('ext-desc_value').item(data_number);
                        var val = textAreaDescVal.value;
                        textAreaDescVal.value = val+'<img class="wide-img" src="'+JSON.parse(xhr.responseText).img_path+'">';
                        console.log(textAreaDescVal.value)
                        console.log(xhr.responseText)
                    };

                    if (tests.progress) {
                        xhr.upload.onprogress = function (event) {
                            if (event.lengthComputable) {
                                var complete = (event.loaded / event.total * 100 | 0);
                                progress.value = progress.innerHTML = complete;
                            }
                        }
                    }

                    xhr.send(formData);
                }
            }

//        if (tests.dnd) {
            holder.ondragover = function () {
                this.className = 'hover';
                return false;
            };
            holder.ondragend = function () {
                this.className = '';
                return false;
            };
            holder.ondrop = function (e) {
                this.className = '';
                e.preventDefault();
                data_number = this.getAttribute('data-number');
                readfiles(e.dataTransfer.files, data_number);
            }
//        } else {
//            fileupload.className = 'hidden';
//            fileupload.querySelector('input').onchange = function () {
//                readfiles(this.files);
//            };
//        }
        i++;
        }
        }
        // =============================================== End DropZone ExtDesc ========================================================== //










        var last_variant = $('.variant-list-panel').size();
        var last_desc = $('.ext-desc-list-panel').size();

        // dodaj opis rozszerzony
        $('.btn.add-ext-desc').on('click', function () {
            var first_row = $('.ext-desc-list-panel:last-child').clone(true).attr('data-ext-desc-nr', last_desc);
            first_row.children().attr('data-ext-desc-nr', last_desc).children().attr('data-ext-desc-nr', last_desc);
//            first_row.find('input.ext-desc_value').val(null);
            $('.ext-desc-list').prepend(first_row);
            $('.ext-desc_name').first().val(null);
            $('.ext-desc_value').first().val(null);
            $('.ext-desc_value').first().html(null);
            var adnr = $('article.dropzone-ext-desc').first().attr('data-number');
            $('article.dropzone-ext-desc').first().attr('data-number', adnr+1);
            var adnr = $('div.holder').first().attr('data-number');
            $('div.holder').first().attr('data-number', adnr+1);
            last_desc++;
            // załaduj Dropzone dla nowego ExtDesc
            loadDropzone();
        })

        // usuniecie ostatniej opcji z wariantu
        $('.remove-variant-option').on('click', function () {
            var r = confirm("Usunąć tą opcję ze wszystkich wariantów produktu?");
            if (r == true) {
                var variant_nr = $(this).data('variant-nr');
                var option_nr = $(this).data('number');
                $('.variant-list-panel > div[data-number="' + option_nr + '"]').remove();
            }
        });
        // dodawanie opcji do wariantu
        $('.variant-list').on('click', '.add-variant-option', function () {
            var variant_nr = $(this).data('variant-nr');
            var option_nr = parseInt($(this).data('number')) + 1;
            var html = '';
            html += '<div class="col-md-3" data-number="' + option_nr + '"><input class="form-control disabled option_name" placeholder="Nazwa opcji" data-number="' + option_nr + '" data-variant-nr="' + variant_nr + '" type="text" ></div>';
            html += '<div class="col-md-8" data-number="' + option_nr + '"><input class="form-control disabled option_value" placeholder="Wartość opcji" data-number="' + option_nr + '" data-variant-nr="' + variant_nr + '" type="text"></div>';
            html += '<div class="col-md-1" data-number="' + option_nr + '" style="padding: 0;"><input class="btn btn-danger remove-variant-option" type="button" value="-" data-number="' + option_nr + '" data-variant-nr="' + variant_nr + '"><input class="btn btn-primary add-variant-option" type="button" value="+" data-number="' + option_nr + '" data-variant-nr="' + variant_nr + '"></div>';
            $('.variant-list-panel').append(html);
        });
        // dodawanie warioantuw nowych (front)
        $('.btn.add-variant').on('click', function () {
            // var last_variant = parseInt($('.variant-list-panel:first-child').data('variant-nr')) + 1;
            var first_row = $('.variant-list-panel:last-child').clone(true).attr('data-variant-nr', last_variant);
            first_row.children().attr('data-variant-nr', last_variant).children().attr('data-variant-nr', last_variant);
            first_row.find('input.option_name').attr('disabled', 'disabled');
            first_row.find('input.option_value').val(null);
            $('.variant-list').prepend(first_row);
            last_variant++;
        });
        // przepisywanie nazwy klucza do wszystkich wariantów
        $('.variant-list-panel:last-child input.option_name').on('keyup', function () {
            $('.variant-list-panel:nth-child(n-1) input.option_name[data-number="' + $(this).data('number') + '"]').val($(this).val())
        });

        // Submit forma, zapis variantow, zapis ext_desc
        $('.submit-form').on('click', function () {

            var form = $('form.form').serializeArray().reduce(function (obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            var values = []
            var key_input = $(this);
            var variant = [];
            var desc_values = {};
            $('.variant-list-panel').each(function () {
                var variant_nr = $(this).data('variant-nr');
                var option = {};
                option.variant_number = variant_nr;
                option.data = {};
                option.price = $('input[data-variant-nr="' + variant_nr + '"][name="variant_price"]').val();
                option.ean = $('input[data-variant-nr="' + variant_nr + '"][name="variant_ean"]').val();
                $('.option_name[data-variant-nr="' + variant_nr + '"]').each(function () {
                    var number = $(this).data('number');
                    var key = $(this).val();
                    var value = $('.option_value[data-number="' + number + '"][data-variant-nr="' + variant_nr + '"]').val();

                    if (key.length > 0 && value.length > 0)
                        option.data[$(this).val()] = value;
                });
                variant.push(option);
            });
//            form.variants = JSON.stringify(variant);
            $('form.form').append("<input type='hidden' name='variants' value='" + JSON.stringify(variant) + "'>");

            // Ext_Desc - doklej do formularza rozszerzone opisy
            $('.ext-desc-list-panel').each(function () {
                var desc_name = $(this).find('input.ext-desc_name').val();
                var desc_value = $(this).find('textarea.ext-desc_value').val()
                if (desc_name.length > 0)
                    desc_values[desc_name] = desc_value;
            });
//            form.desc_values = JSON.stringify(desc_values);
            $('form.form').append("<input type='hidden' name='desc_values' value='" + JSON.stringify(desc_values) + "'>");
            
            //Do popapu admina na stroie
            $(document).find('.item-on-flow').removeClass('thumbnail');
            $(document).find('.item-on-flow').addClass('wideList');

            setTimeout(function(){
            $(document).find('.item-on-flow').removeClass('thumbnail');
                        $(document).find('.item-on-flow').addClass('wideList');
            },2000);

            $.post('{{action("ItemController@store")}}', $('form.form').serialize());

//            $('form.form').submit();

//            $.ajax({
//                method: 'POST',
//                url: "{{action('ItemController@store')}}",
//                data: form,
//                success: function (res) {
//                    if (res.Success)
//                        window.location.href = '/admin/items';
//                }
//            });

        });
    });

    var openFile = function (event) {
        var input = event.target;

        var reader = new FileReader();
        reader.onload = function () {
            var dataURL = reader.result;
            var output = document.getElementById('item-image');
            output.src = dataURL;
        };
        reader.readAsDataURL(input.files[0]);

        // $('#save-image').on('click', function(){
        //   $.ajax({
        //     url: ''
        //   })
        // });

    };
</script>


@stop