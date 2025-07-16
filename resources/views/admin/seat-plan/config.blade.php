@extends('layouts.admin')

@section('title')
Seat Plan Config
@endsection

@section('content')
<div class="container-fluid p-0 ">
    <div class="row ">
        <div class="col-12">
            <div class="white_card mb_30 ">
                <div class="white_card_header">
                    <div class="bulder_tab_wrapper">
                        <ul class="nav" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="Buildings-tab" data-bs-toggle="tab" href="#Buildings" role="tab" aria-controls="Buildings" aria-selected="true">Buildings & Rooms</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Classes-tab" data-bs-toggle="tab" href="#Classes" role="tab" aria-controls="profile" aria-selected="false">Classes & Sections</a>
                            </li>
                        </ul>
                            
                    </div>
                </div>
                <div class="white_card_body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="Buildings" role="tabpanel" aria-labelledby="Buildings-tab">
                            <div class="builder_select">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-6 ">
                                        <label class="form-label" for="#">Header Theme:</label>
                                        <div class="common_select">
                                            <select class="nice_Select wide mb_30" >
                                                <option value="">Dark</option>
                                                <option value="">Lite</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4 col-lg-6 ">
                                        <label class="form-label" for="#">Header Menu Theme:</label>
                                        <div class="common_select">
                                            <select class="nice_Select wide mb_30" >
                                                <option value="">Dark</option>
                                                <option value="">Lite</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4 col-lg-6 ">
                                        <label class="form-label" for="#">Logo Bar Theme:</label>
                                        <div class="common_select">
                                            <select class="nice_Select wide mb_30" >
                                                <option value="">Dark</option>
                                                <option value="">Lite</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Classes" role="tabpanel" aria-labelledby="Classes-tab">
                            <div class="builder_select">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-6 ">
                                        <label class="form-label" for="#">Page Loader:</label>
                                        <div class="common_select">
                                            <select class="nice_Select wide mb_30" >
                                                <option value="">Disabled</option>
                                                <option value="">Spiners</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="devices_btn justify-content-start">
                        <a class="color_button color_button2" href="#">Preview</a>
                        <a class="color_button" href="#">Export</a>
                        <a class="color_button color_button3" href="#">Reset</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection