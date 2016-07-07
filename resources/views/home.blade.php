@extends('layouts.sidebar')

@section('title')
<i class="fa fa-home"></i> <small>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</small> / My Stuff
@endsection

@section('tabs')
<div class="ui tabular menu" style="border-bottom: none; margin-top: 0;">
    <a href="{{ route('me.incidents') }}" class="active item">
        <i class="fa fa-flag"></i> Incidents
    </a>
    <a href="{{ route('me.networks') }}" class="item">
        <i class="fa fa-users"></i> Networks
    </a>
</div>
@endsection

@section('page-content')
<div id="incList" class="ui centered divided grid">
    <div class="sixteen wide column">
        <p>
            List of active incidents you are assigned to.
        </p>

        <div class="ui message" style="text-align: center" v-if="list.length == 0">
            You are not assigned to any incidents.
        </div>
        <table class="ui small table" v-else>
            <thead>
                <tr>
                    <th><i class="fa fa-hashtag"></i></th>
                    <th>Grade</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Updates</th>
                    <th>Due <small>(D:H:M)</small></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="i in list | orderBy 'due_in_raw'" track-by="id">
                    <td>
                        <a href="@{{ i.link }}" class="hover-popup ui" data-content="@{{ i.dets }}" data-variation="basic" data-position="right center">
                            @{{ i.ref }}
                        </a>
                    </td>
                    <td><div class="ui @{{ i.grade_color }} label">@{{ i.grade_name }}</div></td>
                    <td><div class="ui label">@{{ i.type }}</div></td>
                    <td>@{{ i.location }}</td>
                    <td>@{{ i.updates }}</td>
                    <td>
                        <div class="ui red basic label" v-if="i.is_overdue">
                            @{{ i.due_in }}
                        </div>
                        <div class="ui blue basic label" v-else>
                            @{{ i.due_in }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="ui divider"></div>

        <p style="text-align: center">
            In the 'Due' column, <span class="ui small blue basic label">blue times</span> show the amount of time until the next update is
            and <span class="ui small red basic label">red times</span> show how far overdue an incident is (i.e. how
            long since it should have been updated).
        </p>

        <p style="text-align: center">
            You can create an incident by going to one of your <a href="{{ route('me.networks') }}">Networks</a> and adding it there.
        </p>  

        <p style="text-align: center">
            This page will automatically update every 20 seconds.
        </p>

    </div>
</div>

<script>
        var app = new Vue({
            el: '#incList',
            data: {
                list: []
            },
            ready: function() {
                this.update();
            },
            methods: {
                update: function() {
                    $.get('{{url('/iapi/incidents')}}').done(function(data){
                        app.list = $.parseJSON(data);
                    });
                    setTimeout(init_semantic, 500);
                    setTimeout(this.update, 20000);
                }
            }
        })
    </script>
@endsection
