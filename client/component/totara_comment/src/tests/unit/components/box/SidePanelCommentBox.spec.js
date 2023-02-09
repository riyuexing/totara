/**
 * This file is part of Totara Enterprise Extensions.
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
 *
 * Totara Enterprise Extensions is provided only to Totara
 * Learning Solutions LTD's customers and partners, pursuant to
 * the terms and conditions of a separate agreement with Totara
 * Learning Solutions LTD or its affiliate.
 *
 * If you do not have an agreement with Totara Learning Solutions
 * LTD, you may not access, use, modify, or distribute this software.
 * Please contact [licensing@totaralearning.com] for more information.
 *
 * @author Tatsuhiro Kirihara <tatsuhiro.kirihara@totaralearning.com>
 * @module totara_comment
 */

import { shallowMount } from '@vue/test-utils';
import component from 'totara_comment/components/box/SidePanelCommentBox.vue';

jest.mock('tui/apollo_client', () => null);
jest.mock('tui/tui', () => null);

describe('SidePanelCommentBox', () => {
  const template = {
    component: '?',
    area: '?',
    instanceId: '?',
  };

  it('check reallyCommentAble without showComment or interactor', () => {
    const propsData = Object.assign({}, template);
    const wrapper = shallowMount(component, { propsData });
    expect(wrapper.vm.reallyCommentAble).toBeTrue();
  });

  it.each([true, false])(
    'check reallyCommentAble with showComment',
    showComment => {
      const propsData = Object.assign({}, template, { showComment });
      const wrapper = shallowMount(component, { propsData });
      expect(wrapper.vm.reallyCommentAble).toBe(showComment);
    }
  );

  it.each([true, false])(
    'check reallyCommentAble with interactor',
    canComment => {
      const propsData = Object.assign({}, template, {
        interactor: { can_comment: canComment },
      });
      const wrapper = shallowMount(component, { propsData });
      expect(wrapper.vm.reallyCommentAble).toBe(canComment);
    }
  );
});