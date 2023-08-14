@if($item->exists)

    <div>
        <div class="text-lg my-4">{{ $element->label() }}</div>

        <x-moonshine::form
            :action="route('moonshine-roles-permissions.roles.attach-permissions-to-role', $item)"
            method="post"
        >

            @foreach(moonshine()->getResources() as $resource)

                @php
                    $resourceName = class_basename($resource);
                @endphp

                @if($element->hasAnyResourcePermissions($resource))
                    <div>
                        <div
                            class="text-md my-4">
                            {{ $resource->title() }}
                        </div>

                        <div class="flex items-center justify-start space-x-4">
                            @foreach($resource->gateAbilities() as $ability)

                                @php
                                    $permission = $element->getPermissionName($resourceName, $ability);
                                @endphp

                                @if($element->hasPermission($permission))

                                    <x-moonshine::form.input-wrapper
                                        name="permissions[{{ $permission }}]"
                                        :label="$ability"
                                        :beforeLabel="true"
                                        class="form-group-inline {{ $element->existPermission($permission) ?: 'hidden'}}"
                                        :id="str('permissions_' . get_class($resource) . '_' . $ability)->slug('_')"
                                    >

                                        <x-moonshine::form.input
                                            :id="str('permissions_' . get_class($resource) . '_' . $ability)->slug('_')"
                                            type="checkbox"
                                            name="permissions[{{ $permission }}]]"
                                            value="1"
                                            :checked="$element->existHasPermission($item, $permission)"
                                        />
                                    </x-moonshine::form.input-wrapper>

                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

            @endforeach

            @if($element->hasAnyPermission($element->getCustomPermissions(moonshine()->getResources())))
                <div
                    class="text-md my-4">
                    Custom Permissions
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-start space-x-4">
                @foreach($element->getCustomPermissions(moonshine()->getResources()) as $key => $permission)

                    <div>

                        @if($element->hasPermission($permission))

                            <x-moonshine::form.input-wrapper
                                name="permissions[{{ $permission }}]"
                                :label="$permission"
                                :beforeLabel="true"
                                class="form-group-inline {{ $element->existPermission($permission) ?: 'hidden'}}"
                                :id="str('permissions_' . $permission)->slug('_')"
                            >

                                <x-moonshine::form.input
                                    :id="str('permissions_' . $permission)->slug('_')"
                                    type="checkbox"
                                    name="permissions[{{ $permission }}]]"
                                    value="1"
                                    :checked="$element->existHasPermission($item, $permission)"
                                />
                            </x-moonshine::form.input-wrapper>

                        @endif
                    </div>
                @endforeach
            </div>

            <x-slot:button type="submit" class="form_submit_button my-5">
                {{ trans('moonshine::ui.save') }}
            </x-slot:button>
        </x-moonshine::form>
    </div>
@endif
