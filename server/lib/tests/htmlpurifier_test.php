<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Unit tests for the HTMLPurifier integration
 *
 * @package    core
 * @category   phpunit
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * HTMLPurifier test case
 *
 * @package    core
 * @category   phpunit
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_htmlpurifier_testcase extends basic_testcase {

    /**
     * Verify _blank target is allowed.
     */
    public function test_allow_blank_target() {
        // Totara: HTMLPurifier adds extra rel attributes automatically.
        $text = '<a href="http://moodle.org" target="_blank">Some link</a>';
        $expected = '<a href="http://moodle.org" target="_blank" rel="noreferrer noopener">Some link</a>';
        $result = format_text($text, FORMAT_HTML);
        $this->assertSame($expected, $result);

        $text = '<a href="http://moodle.org">Some link</a>';
        $expected = '<a href="http://moodle.org">Some link</a>';
        $result = format_text($text, FORMAT_HTML);
        $this->assertSame($expected, $result);

        $result = format_text('<a href="http://moodle.org" target="some">Some link</a>', FORMAT_HTML);
        $this->assertSame('<a href="http://moodle.org">Some link</a>', $result);
    }

    /**
     * Verify our nolink tag accepted.
     */
    public function test_nolink() {
        // We can not use format text because nolink changes result.
        $text = '<nolink><div>no filters</div></nolink>';
        $result = purify_html($text, array());
        $this->assertSame($text, $result);

        $text = '<nolink>xxx<em>xx</em><div>xxx</div></nolink>';
        $result = purify_html($text, array());
        $this->assertSame($text, $result);
    }

    /**
     * Verify our tex tag accepted.
     */
    public function test_tex() {
        $text = '<tex>a+b=c</tex>';
        $result = purify_html($text, array());
        $this->assertSame($text, $result);
    }

    /**
     * Verify our algebra tag accepted.
     */
    public function test_algebra() {
        $text = '<algebra>a+b=c</algebra>';
        $result = purify_html($text, array());
        $this->assertSame($text, $result);
    }

    /**
     * Verify our hacky multilang works.
     */
    public function test_multilang() {
        $text = '<lang lang="en">hmmm</lang><lang lang="anything">hm</lang>';
        $result = purify_html($text, array());
        $this->assertSame($text, $result);

        $text = '<span lang="en" class="multilang">hmmm</span><span lang="anything" class="multilang">hm</span>';
        $result = purify_html($text, array());
        $this->assertSame($text, $result);

        $text = '<span lang="en">hmmm</span>';
        $result = purify_html($text, array());
        $this->assertNotSame($text, $result);

        // Keep standard lang tags.

        $text = '<span lang="de_DU" class="multilang">asas</span>';
        $result = purify_html($text, array());
        $this->assertSame($text, $result);

        $text = '<lang lang="de_DU">xxxxxx</lang>';
        $result = purify_html($text, array());
        $this->assertSame($text, $result);
    }

    /**
     * Tests the 'allowid' option for format_text.
     */
    public function test_format_text_allowid() {
        // Start off by not allowing ids (default).
        $options = array(
            'nocache' => true
        );
        $result = format_text('<div id="example">Frog</div>', FORMAT_HTML, $options);
        $this->assertSame('<div>Frog</div>', $result);

        // Now allow ids.
        $options['allowid'] = true;
        $result = format_text('<div id="example">Frog</div>', FORMAT_HTML, $options);
        $this->assertSame('<div id="example">Frog</div>', $result);
    }

    /**
     * Test if linebreaks kept unchanged.
     */
    public function test_line_breaking() {
        $text = "\n\raa\rsss\nsss\r";
        $this->assertSame($text, purify_html($text));
    }

    /**
     * Test fixing of strict problems.
     */
    public function test_tidy() {
        $text = "<p>xx";
        $this->assertSame('<p>xx</p>', purify_html($text));

        $text = "<P>xx</P>";
        $this->assertSame('<p>xx</p>', purify_html($text));

        $text = "xx<br>";
        $this->assertSame('xx<br />', purify_html($text));
    }

    /**
     * Test nesting - this used to cause problems in earlier versions.
     */
    public function test_nested_lists() {
        $text = "<ul><li>One<ul><li>Two</li></ul></li><li>Three</li></ul>";
        $this->assertSame($text, purify_html($text));
    }

    /**
     * Test that XSS protection works, complete smoke tests are in htmlpurifier itself.
     */
    public function test_cleaning_nastiness() {
        $text = "x<SCRIPT>alert('XSS')</SCRIPT>x";
        $this->assertSame('xx', purify_html($text));

        $text = '<DIV STYLE="background-image:url(javascript:alert(\'XSS\'))">xx</DIV>';
        $this->assertSame('<div>xx</div>', purify_html($text));

        $text = '<DIV STYLE="width:expression(alert(\'XSS\'));">xx</DIV>';
        $this->assertSame('<div>xx</div>', purify_html($text));

        $text = 'x<IFRAME SRC="javascript:alert(\'XSS\');"></IFRAME>x';
        $this->assertSame('xx', purify_html($text));

        $text = 'x<OBJECT TYPE="text/x-scriptlet" DATA="http://ha.ckers.org/scriptlet.html"></OBJECT>x';
        $this->assertSame('xx', purify_html($text));

        $text = 'x<EMBED SRC="http://ha.ckers.org/xss.swf" AllowScriptAccess="always"></EMBED>x';
        $this->assertSame('xx', purify_html($text));

        $text = 'x<form></form>x';
        $this->assertSame('xx', purify_html($text));
    }

    /**
     * Test internal function used for clean_text() speedup.
     */
    public function test_is_purify_html_necessary() {
        // First our shortcuts.
        $text = "";
        $this->assertFalse(is_purify_html_necessary($text));
        $this->assertSame($text, purify_html($text));

        $text = "666";
        $this->assertFalse(is_purify_html_necessary($text));
        $this->assertSame($text, purify_html($text));

        $text = "abc\ndef \" ' ";
        $this->assertFalse(is_purify_html_necessary($text));
        $this->assertSame($text, purify_html($text));

        $text = "abc\n<p>def</p>efg<p>hij</p>";
        $this->assertFalse(is_purify_html_necessary($text));
        $this->assertSame($text, purify_html($text));

        $text = "<br />abc\n<p>def<em>efg</em><strong>hi<br />j</strong></p>";
        $this->assertFalse(is_purify_html_necessary($text));
        $this->assertSame($text, purify_html($text));

        // Now failures.
        $text = "&nbsp;";
        $this->assertTrue(is_purify_html_necessary($text));

        $text = "Gin & Tonic";
        $this->assertTrue(is_purify_html_necessary($text));

        $text = "Gin > Tonic";
        $this->assertTrue(is_purify_html_necessary($text));

        $text = "Gin < Tonic";
        $this->assertTrue(is_purify_html_necessary($text));

        $text = "<div>abc</div>";
        $this->assertTrue(is_purify_html_necessary($text));

        $text = "<span>abc</span>";
        $this->assertTrue(is_purify_html_necessary($text));

        $text = "<br>abc";
        $this->assertTrue(is_purify_html_necessary($text));

        $text = "<p class='xxx'>abc</p>";
        $this->assertTrue(is_purify_html_necessary($text));

        $text = "<p>abc<em></p></em>";
        $this->assertTrue(is_purify_html_necessary($text));

        $text = "<p>abc";
        $this->assertTrue(is_purify_html_necessary($text));
    }

    public function test_allowed_schemes() {
        // First standard schemas.
        $text = '<a href="http://www.example.com/course/view.php?id=5">link</a>';
        $this->assertSame($text, purify_html($text));

        $text = '<a href="https://www.example.com/course/view.php?id=5">link</a>';
        $this->assertSame($text, purify_html($text));

        $text = '<a href="ftp://user@ftp.example.com/some/file.txt">link</a>';
        $this->assertSame('<a>link</a>', purify_html($text));

        $text = '<a href="nntp://example.com/group/123">link</a>';
        $this->assertSame($text, purify_html($text));

        $text = '<a href="news:groupname">link</a>';
        $this->assertSame($text, purify_html($text));

        $text = '<a href="mailto:user@example.com">link</a>';
        $this->assertSame($text, purify_html($text));

        // Extra schemes allowed in moodle.
        $text = '<a href="irc://irc.example.com/3213?pass">link</a>';
        $this->assertSame($text, purify_html($text));

        $text = '<a href="rtsp://www.example.com/movie.mov">link</a>';
        $this->assertSame($text, purify_html($text));

        $text = '<a href="rtmp://www.example.com/video.f4v">link</a>';
        $this->assertSame($text, purify_html($text));

        $text = '<a href="teamspeak://speak.example.com/?par=val?par2=val2">link</a>';
        $this->assertSame($text, purify_html($text));

        $text = '<a href="gopher://gopher.example.com/resource">link</a>';
        $this->assertSame('<a>link</a>', purify_html($text));

        $text = '<a href="mms://www.example.com/movie.mms">link</a>';
        $this->assertSame($text, purify_html($text));

        // Now some borked or dangerous schemes.
        $text = '<a href="javascript://www.example.com">link</a>';
        $this->assertSame('<a>link</a>', purify_html($text));

        $text = '<a href="hmmm://www.example.com">link</a>';
        $this->assertSame('<a>link</a>', purify_html($text));
    }

    /**
     * Tests media tags.
     *
     * @dataProvider media_tags_provider
     * @param string $mediatag HTML media tag
     * @param string $expected expected result
     */
    public function test_media_tags($mediatag, $expected) {
        $actual = format_text($mediatag, FORMAT_MOODLE, ['filter' => false]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test cases for the test_media_tags test.
     */
    public function media_tags_provider() {
        // Takes an array of attributes, then generates a test for each of them.
        $generatetestcases = function($prefix, array $attrs, array $templates) {
            return array_reduce($attrs, function($carry, $attr) use ($prefix, $templates) {
                $testcase = [$prefix . '/' . $attr => [
                    sprintf($templates[0], $attr),
                    sprintf($templates[1], $attr)
                ]];
                return empty(array_values($carry)[0]) ? $testcase : $carry + $testcase;
            }, [[]]);
        };

        $audioattrs = [
            'preload="auto"', 'autoplay=""', 'loop=""', 'muted=""', 'controls=""',
            'crossorigin="anonymous"', 'crossorigin="use-credentials"'
        ];
        $videoattrs = [
            'crossorigin="anonymous"', 'crossorigin="use-credentials"',
            'poster="https://upload.wikimedia.org/wikipedia/en/1/14/Space_jam.jpg"',
            'preload="auto"', 'autoplay=""', 'playsinline=""', 'loop=""', 'muted=""',
            'controls=""', 'width="420"', 'height="69"'
        ];
        return $generatetestcases('Plain audio', $audioattrs + ['src="http://example.com/jam.wav"'], [
                '<audio %1$s>Looks like you can\'t slam the jams.</audio>',
                '<div class="text_to_html"><audio %1$s>Looks like you can\'t slam the jams.</audio></div>'
            ]) + $generatetestcases('Audio with one source', $audioattrs, [
                '<audio %1$s><source src="http://example.com/getup.wav">No tasty jams for you.</audio>',
                '<div class="text_to_html">' .
                    '<audio %1$s>' .
                        '<source src="http://example.com/getup.wav" />' .
                        'No tasty jams for you.' .
                    '</audio>' .
                '</div>'
            ]) + $generatetestcases('Audio with multiple sources', $audioattrs, [
                '<audio %1$s>' .
                    '<source src="http://example.com/getup.wav" type="audio/wav">' .
                    '<source src="http://example.com/getup.mp3" type="audio/mpeg">' .
                    '<source src="http://example.com/getup.ogg" type="audio/ogg">' .
                    'No tasty jams for you.' .
                '</audio>',
                '<div class="text_to_html">' .
                    '<audio %1$s>' .
                        '<source src="http://example.com/getup.wav" type="audio/wav" />' .
                        '<source src="http://example.com/getup.mp3" type="audio/mpeg" />' .
                        '<source src="http://example.com/getup.ogg" type="audio/ogg" />' .
                        'No tasty jams for you.' .
                    '</audio>' .
                '</div>'
            ]) + $generatetestcases('Audio with sources and tracks', $audioattrs, [
                '<audio %1$s>' .
                    '<source src="http://example.com/getup.wav" type="audio/wav">' .
                    '<track kind="subtitles" src="http://example.com/subtitles_en.vtt" label="English" srclang="en">' .
                    '<track kind="subtitles" src="http://example.com/subtitles_es.vtt" label="Espanol" srclang="es">' .
                    'No tasty jams for you.' .
                '</audio>',
                '<div class="text_to_html">' .
                    '<audio %1$s>' .
                        '<source src="http://example.com/getup.wav" type="audio/wav" />' .
                        '<track kind="subtitles" src="http://example.com/subtitles_en.vtt" label="English" srclang="en" />' .
                        '<track kind="subtitles" src="http://example.com/subtitles_es.vtt" label="Espanol" srclang="es" />' .
                        'No tasty jams for you.' .
                    '</audio>' .
                '</div>'
            ]) + $generatetestcases('Plain video', $videoattrs + ['src="http://example.com/prettygood.mp4'], [
                '<video %1$s>Oh, that\'s pretty bad 😦</video>',
                '<div class="text_to_html"><video %1$s>Oh, that\'s pretty bad 😦</video></div>'
            ]) + $generatetestcases('Video with illegal subtag', $videoattrs + ['src="http://example.com/prettygood.mp4'], [
                '<video %1$s><subtag></subtag>Oh, that\'s pretty bad 😦</video>',
                '<div class="text_to_html"><video %1$s>Oh, that\'s pretty bad 😦</video></div>'
            ]) + $generatetestcases('Video with legal subtag', $videoattrs + ['src="http://example.com/prettygood.mp4'], [
                '<video %1$s>Did not work <a href="http://example.com/prettygood.mp4">click here to download</a></video>',
                '<div class="text_to_html"><video %1$s>Did not work <a href="http://example.com/prettygood.mp4">' .
                'click here to download</a></video></div>'
            ]) + $generatetestcases('Source tag without video or audio', $videoattrs, [
                'some text <source src="http://example.com/getup.wav" type="audio/wav"> the end',
                '<div class="text_to_html">some text  the end</div>'
            ]) + $generatetestcases('Video with one source', $videoattrs, [
                '<video %1$s><source src="http://example.com/prettygood.mp4">Oh, that\'s pretty bad 😦</video>',
                '<div class="text_to_html">' .
                    '<video %1$s>' .
                        '<source src="http://example.com/prettygood.mp4" />' .
                        'Oh, that\'s pretty bad 😦' .
                    '</video>' .
                '</div>'
            ]) + $generatetestcases('Video with multiple sources', $videoattrs, [
                '<video %1$s>' .
                    '<source src="http://example.com/prettygood.mp4" type="video/mp4">' .
                    '<source src="http://example.com/eljefe.mp4" type="video/mp4">' .
                    '<source src="http://example.com/turnitup.mov" type="video/mov">' .
                    'Oh, that\'s pretty bad 😦' .
                '</video>',
                '<div class="text_to_html">' .
                    '<video %1$s>' .
                        '<source src="http://example.com/prettygood.mp4" type="video/mp4" />' .
                        '<source src="http://example.com/eljefe.mp4" type="video/mp4" />' .
                        '<source src="http://example.com/turnitup.mov" type="video/mov" />' .
                        'Oh, that\'s pretty bad 😦' .
                    '</video>' .
                '</div>'
            ]) + $generatetestcases('Video with sources and tracks', $audioattrs, [
                '<video %1$s>' .
                    '<source src="http://example.com/getup.wav" type="audio/wav">' .
                    '<track kind="subtitles" src="http://example.com/subtitles_en.vtt" label="English" srclang="en">' .
                    '<track kind="subtitles" src="http://example.com/subtitles_es.vtt" label="Espanol" srclang="es">' .
                    'No tasty jams for you.' .
                '</video>',
                '<div class="text_to_html">' .
                    '<video %1$s>' .
                        '<source src="http://example.com/getup.wav" type="audio/wav" />' .
                        '<track kind="subtitles" src="http://example.com/subtitles_en.vtt" label="English" srclang="en" />' .
                        '<track kind="subtitles" src="http://example.com/subtitles_es.vtt" label="Espanol" srclang="es" />' .
                    'No tasty jams for you.' .
                    '</video>' .
                '</div>'
            ]) + ['Video with invalid crossorigin' => [
                    '<video src="http://example.com/turnitup.mov" crossorigin="can i pls hab?">' .
                        'Oh, that\'s pretty bad 😦' .
                    '</video>',
                    '<div class="text_to_html">' .
                        '<video src="http://example.com/turnitup.mov">' .
                           'Oh, that\'s pretty bad 😦' .
                        '</video>' .
                    '</div>'
            ]] + ['Audio with invalid crossorigin' => [
                    '<audio src="http://example.com/getup.wav" crossorigin="give me. the jams.">' .
                        'nyemnyemnyem' .
                    '</audio>',
                    '<div class="text_to_html">' .
                        '<audio src="http://example.com/getup.wav">' .
                            'nyemnyemnyem' .
                        '</audio>' .
                    '</div>'
            ]] + ['Other attributes' => [
                '<video src="http://example.com/turnitdown.mov" class="nofilter" data-something="data attribute" someattribute="somevalue" onclick="boom">' .
                    '<source src="http://example.com/getup.wav" type="audio/wav" class="shouldberemoved" data-sourcedata="source data" onmouseover="kill session" />' .
                    '<track src="http://example.com/subtitles_en.vtt" class="shouldberemoved" data-trackdata="track data" onmouseover="removeme" />' .
                    'Do not remove attribute class but remove other attributes' .
                '</video>',
                '<div class="text_to_html">' .
                    '<video src="http://example.com/turnitdown.mov" class="nofilter">' .
                        '<source src="http://example.com/getup.wav" type="audio/wav" />' .
                        '<track src="http://example.com/subtitles_en.vtt" />' .
                        'Do not remove attribute class but remove other attributes' .
                    '</video>' .
                '</div>'
            ]];
    }

    /**
     * Verify display is allowed in CSS
     */
    public function test_display_in_css() {
        $text = '<div style="color:#FF0000;display:none;">HELLO</div>';
        $result = purify_html($text, array());
        $this->assertSame($text, $result);
    }
}
