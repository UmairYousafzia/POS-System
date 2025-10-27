@extends('layouts.app')
@section('title', 'User Profile')
@section('content')

    <div class="container">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> Edit Role</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{route('settings.roles.index')}}"  class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{ route('settings.roles.update', $role->id) }}" id="myForm">
            @csrf
            @method('put')

            <div class="d-flex align-items-center pr-2 ">
                <label for="role" class="fs-4" >Role </label>
                <input type="text" class="form-control" id="role" name="role" value="{{ old('role') ?: $role->name }}" placeholder="Role Name...">
            </div>
            <hr/>
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered">
                            <thead class="text-center">
                            <tr>
                                <th>Modules</th>
                                <th colspan="5">Permissions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><h6>Profile</h6> </td>
                                <td colspan="4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="profile.edit" value="profile.edit" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('profile.edit', $permissions)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="profile.edit">Can Edit </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><h6>POS</h6> </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.sell" value="pos.sell" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.sell', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.sell">Sell</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.purchase" value="pos.purchase" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.purchase', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.purchase">Purchase</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.products.view" value="pos.products.view" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.products.view', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.products.view">Products View</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.products.manage" value="pos.products.manage" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.products.manage', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.products.manage">Products Manage</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.parties.view" value="pos.parties.view" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.parties.view', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.parties.view">Clients & Suppliers View</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.parties.manage" value="pos.parties.manage" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.parties.manage', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.parties.manage">Clients & Suppliers Manage</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.sales.view" value="pos.sales.view" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.sales.view', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.sales.view">Sales History</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.purchases.view" value="pos.purchases.view" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.purchases.view', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.purchases.view">Purchases History</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><h6>Expenses & Stock</h6> </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.expenses.view" value="pos.expenses.view" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.expenses.view', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.expenses.view">Expenses View</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.expenses.manage" value="pos.expenses.manage" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.expenses.manage', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.expenses.manage">Expenses Manage</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.stock.view" value="pos.stock.view" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.stock.view', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.stock.view">Stock On Hand</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.locations.manage" value="pos.locations.manage" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.locations.manage', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.locations.manage">Locations Manage</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.warehouses.manage" value="pos.warehouses.manage" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.warehouses.manage', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.warehouses.manage">Warehouses Manage</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><h6>Backup</h6> </td>
                                <td colspan="4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pos.backup.manage" value="pos.backup.manage" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('pos.backup.manage', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="pos.backup.manage">Backup Manage</label>
                                    </div>
                                </td>
                            </tr>


                            <tr >
                                <td><h6>Users </h6> </td>
                                <td>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input view_all" type="checkbox" data-module="users" id="users_view_all" value="user.view_all" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('user.view_all', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="users_view_all">Can view All </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input view_own" type="checkbox" data-module="users" id="users_view_own" value="user.view_own" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('user.view_own', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="users_view_own" >Can View Own</label>
                                    </div>
                                </td>
                                <td>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="user.creat" value="user.create" name="permissions[]"  {{ (is_array(old('permissions', $permissions)) and in_array('user.create', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="user.creat">Can creat </label>
                                    </div>
                                </td>
                                <td>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="user.edit" value="user.edit" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('user.edit', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="user.edit">Can edit </label>
                                    </div>
                                </td>

                                <td>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="user.delete" value="user.delete" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('user.delete', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="user.delete">Can delete </label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td><h6>Roles </h6> </td>
                                <td>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="role.view" value="role.view" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('role.view', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="role.view">Can view </label>
                                    </div>
                                </td>
                                <td>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="role.create" value="role.create" name="permissions[]"  {{ (is_array(old('permissions', $permissions)) and in_array('role.create', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="role.creat">Can creat </label>
                                    </div>
                                </td>
                                <td>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="user.edit" value="role.edit" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('role.edit', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="role.edit">Can edit </label>
                                    </div>
                                </td>

                                <td>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="user.delete" value="role.delete" name="permissions[]" {{ (is_array(old('permissions', $permissions)) and in_array('role.delete', $permissions)) ? ' checked' : '' }}>
                                        <label class="form-check-label" for="role.delete">Can delete </label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection

@section('script')
    <script>

        $(document).ready(function () {
            $('.view_all').on('click', function () {
                var module_name = $(this).data('module');
                if ($('#' + module_name + '_view_all').is(':checked')) {
                    $("#" + module_name + '_view_own').prop('checked', false)
                }
            });

            $('.view_own').on('click', function () {
                var module_name = $(this).data('module');
                if ($('#' + module_name + '_view_own').is(':checked')) {
                    $("#" + module_name + '_view_all').prop('checked', false)
                }
            });
        });

    </script>
@endsection





