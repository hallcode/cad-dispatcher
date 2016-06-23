@extends('layouts.app')

@section('title')
<i class="fa fa-hashtag"></i>
<small>
<a href="{{ route('network.show', ['network' => $network->code]) }}">
    {{ $network->name }}
</a></small> / {{$incident->set_date}} / <b>{{$incident->ref}}</b>
@endsection

@section('buttons')
<a class="ui secondary button" href="{{ route('incident.edit', ['network' => $network->code, 'ref' => $incident->ref, 'date' => $incident->url_date]) }}">
    <i class="fa fa-pencil"></i>
    Edit
</a>
<a class="ui secondary button" href="{{ route('incident.addUpdate', ['network' => $network->code, 'ref' => $incident->ref, 'date' => $incident->url_date]) }}">
    <i class="fa fa-plus"></i>
    Update
</a>
@endsection

@section('tabs')
<!--
<a class="active item" href="{{$incident->link}}">
    <i class="fa fa-asterisk"></i>
    Details
</a>
<a class="item">
    <i class="fa fa-map-o"></i>
    Map
</a>
-->
@endsection

@section('content')
<div class="ui stackable divided grid">
    <div class="nine wide column">
        <table class="ui basic attribute table">
            <tbody>
                <tr>
                    <td class="four wide">Ref</td>
                    <td class="eleven wide">
                        {{$incident->set_date}} / {{$incident->ref}}
                    </td>
                </tr>
                <tr>
                    <td>Info</td>
                    <td>
                        <div class="ui {{$incident->grade->color}} label">{{ $incident->grade->name }}</div>
                        <div class="ui label">{{ $incident->type->name }}</div>
                    </td>
                </tr>
                <tr>
                    <td>Details</td>
                    <td>{{$incident->dets}}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td>{{$incident->location->formatted_address}}</td>
                </tr>
                <tr>
                    @if ($incident->is_overdue == false)
                        <td>Next Update Due <small>dd:hh:mm</small></td>
                    @else
                        <td>Update Overdue By <small>dd:hh:mm</small></td>
                    @endif
                    <td>{{$incident->due_in}}</td> 
                </tr>
                <tr>
                    <td>Users Assigned</td>
                    @if ($incident->users->count() == 0)
                    <td>(None)</td>
                    @else
                    <td>
                    @foreach ($incident->users as $user)
                        {!! $user->label !!}
                    @endforeach
                    </td>
                    @endif
                </tr>
            </tbody>
        </table>

        <h4>Updates ({{$incident->updates->count()}})</h4>
        
        @if ($incident->updates->count() == 0)
        <p>There are no updates to show.</p>
        @else
        @foreach ($incident->updates as $update)
        <div class="ui fluid card">
            <div class="content">
                <div class="meta">{{ date('d M Y @ H:i', strtotime($update->created_at)) }} from 
                    <div class="ui horizontal bulleted link list">
                    @foreach ($update->users as $user)
                    <div class="item">{{ $user->first_name }} {{ $user->last_name }} {{ $user->serial }}</div>
                    @endforeach
                    </div>
                </div>
                <p>
                    {{$update->dets}}
                </p>
                <div class="description"><i class="fa fa-map-marker"></i>{{ $update->location->formatted_address }}</div>
            </div>
        </div>
        @endforeach
        @endif

    </div>
    <div class="seven wide column">
    <div style="height: 400px">
    {!! $map->render() !!}
    </div>
    <!--
        <h4>Uploads (0)</h4>
        <div class="ui four doubling cards">
            <a href="#" class="ui card">
                <div class="image">
                    <img src="http://ichef.bbci.co.uk/news/660/cpsprodpb/A2E4/production/_89400714_gettyimages-522829204.jpg">
                </div>
            </a>
            <a href="#" class="ui card">
                <div class="image">
                    <img src="http://ichef.bbci.co.uk/news/660/cpsprodpb/A2E4/production/_89400714_gettyimages-522829204.jpg">
                </div>
            </a>
            <a href="#" class="ui card">
                <div class="image">
                    <img src="http://ichef.bbci.co.uk/news/660/cpsprodpb/A2E4/production/_89400714_gettyimages-522829204.jpg">
                </div>
            </a>
            <a href="#" class="ui card">
                <div class="image">
                    <img src="http://ichef.bbci.co.uk/news/660/cpsprodpb/A2E4/production/_89400714_gettyimages-522829204.jpg">
                </div>
            </a>
        </div>
        <div class="ui fluid card">
            <div class="content">
                <div class="right floated meta">JPEG / 52kb</div>
                <i class="fa fa-file-image-o"></i> <a href="#">Document Title</a>
                <div class="description">Description of the content etc.</div>
            </div>
            <div class="content">
                <div class="right floated meta">PDF / 52kb</div>
                <i class="fa fa-file-pdf-o"></i> <a href="#">Document Title</a>
                <div class="description">Description of the content etc.</div>
            </div>
        </div>
        -->
    </div>
</div>
<div class="ui divider" style="margin-top: 2rem"></div>
<p style="text-align: center">
This page does not automatically update.
</p>
@endsection