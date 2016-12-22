@extends('layouts.frontend.app')
@section('head')
    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
@endsection
@section('content')
    <div class="detail_box2">
        <div class="about_info">
            <h1>About Me </h1>
            <p><img alt="" src="{{asset('frontend/images/black-drop.png')}}"></p>
        </div>

        <ul class="about_me_info_ul">
            <li>
                Name: <?php echo $profileHolderObj->getFirstname().' '.$profileHolderObj->getLastname();?>
            </li>

            <li>
                Location: <?php echo $profileHolderObj->getAddress();?>
            </li>

            <li>
                Date of Birth:
                <?php echo $profileHolderObj->getUserProfile()->getDob()->format('M d, Y');?>
            </li>

            <li>Gender: <?php echo $profileHolderObj->getGender();?></li>

            <li> Marital status: <?php echo $profileHolderObj->getUserProfile()->getMarital_status();?></li>

            <li>
                Email:
                <?php echo $profileHolderObj->getEmail();?>
            </li>

            <li>
                Phone: <?php echo $profileHolderObj->getUserProfile()->getPhoneNumber();?>
            </li>

            <li>
                Occupation: <?php echo $profileHolderObj->getUserProfile()->getOccupation();?>
            </li>

            <li>
                Work:
                <?php echo $profileHolderObj->getUserProfile()->getWork();?>
            </li>

            <li>
                School:
                <?php echo $profileHolderObj->getUserProfile()->getschool();?>
            </li>

            <li>
                College:
                <?php echo $profileHolderObj->getUserProfile()->getCollege();?>
            </li>

            </ul>

    </div>
@endsection
