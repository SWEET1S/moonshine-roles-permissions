@if($item->exists)

    @php
        $permissions = Spatie\Permission\Models\Permission::all()->pluck('name')->toArray();
    @endphp

    <div>
        <div class="text-lg my-4">{{ $element->label() }}</div>

        <x-moonshine::form
            :action="route('moonshine-roles-permissions.roles.attach-permissions-to-role', $item)"
            method="post"
        >

            @foreach(moonshine()->getResources() as $resource)

                @php
                    $resourceName = explode('\\',get_class($resource))[count(explode('\\',get_class($resource))) - 1];
                @endphp

                <div>
                    <div class="text-md my-4 {{ strpos(implode($permissions), $resourceName) === false ? 'hidden' : '' }}">
                        {{ $resource->title() }}
                    </div>

                    <div class="flex items-center justify-start space-x-4">
                        @foreach($resource->gateAbilities() as $ability)

                            @php
                                $permission = $resourceName.".".$ability
                            @endphp

                            <x-moonshine::form.input-wrapper
                                name="permissions[{{ $permission }}]"
                                :label="$ability"
                                :beforeLabel="true"
                                class="form-group-inline {{ in_array($permission, $permissions) ?: 'hidden'}}"
                                :id="str('permissions_' . get_class($resource) . '_' . $ability)->slug('_')"
                            >

                                <x-moonshine::form.input
                                    :id="str('permissions_' . get_class($resource) . '_' . $ability)->slug('_')"
                                    type="checkbox"
                                    name="permissions[{{ $permission }}]]"
                                    value="1"
                                    :checked="in_array($permission, $permissions) ? $item->hasPermissionTo($permission) : false"
                                />
                            </x-moonshine::form.input-wrapper>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <x-slot:button type="submit" class="form_submit_button">
                {{ trans('moonshine::ui.save') }}
            </x-slot:button>
        </x-moonshine::form>
    </div>
@endif
