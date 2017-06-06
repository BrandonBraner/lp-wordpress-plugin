<?php

namespace LeadpagesWP\Helpers;


class LeadpageType
{

    public static function get_front_lead_page() {
        $v = get_option( 'leadpages_front_page_id', false );

        return ( $v == '' ) ? false : $v;
    }

    public static function set_front_lead_page( $id ) {
        update_option( 'leadpages_front_page_id', $id );
    }

    public static function is_front_page( $id ) {
        $front = self::get_front_lead_page();

        return ( $id == $front && $front !== false );
    }

    public static function get_wg_lead_page() {
        $v = get_option( 'leadpages_wg_page_id', false );

        return ( $v == '' ) ? false : $v;
    }

    public static function set_wg_lead_page( $id ) {
        update_option( 'leadpages_wg_page_id', $id );
    }

    public static function get_404_lead_page() {
        $v = get_option( 'leadpages_404_page_id', false );

        return ( $v == '' ) ? false : $v;
    }

    public static function set_404_lead_page( $id ) {
        update_option( 'leadpages_404_page_id', $id );
    }

    public static function is_nf_page($id){
        $nf = self::get_404_lead_page();

        return ( $id == $nf && $nf !== false );
    }

	/**
	 * Modify meta 'leadpages-served-by' before caching/output html from WP
	 *
     * ex. <meta name="leadpages-served-by" content=""/>
	 * 
	 * @todo generalize the dom selector for reuse on other tags.
	 *
	 * @return string html
	 */
	public static function modifyMetaServedBy($html, $new_value = 'wordpress')
	{
		$dom = new \DOMDocument;
		$dom->loadHTML($html);
		
		$xpath = new \DOMXPath($dom);
		$elem = $xpath->query("//meta[@name='leadpages-served-by']")->item(0);
		if ($elem) {
			$elem->setAttribute('content', $new_value);
		} else {
			$elem = static::createMetaTag('leadpages-served-by', $new_value);
			$dom = static::appendElementToHeadTag($dom, $elem);
		}

		return $dom->saveHTML();	
	}

	/**
	 * Helper to fetch value of a meta tag in the dom
	 *
	 * @param DOMDocument $dom 
	 * @param string      $name meta tag name attribute
	 *
	 * @return string content of <meta> tag
	 */
	public static function lookupMetaByName(\DOMDocument $dom, $name)
	{
		$xpath = new \DOMXPath($dom);
		$meta = $xpath->query("//meta[@name='{$name}']")
			->item(0);

		return $meta ? $meta->getAttribute('content') : null;
	}

	/**
	 * Helper to create a meta tag DOMElement
	 *
	 * @todo Move to separate library
	 *
	 * @return DOMElement
	 */
	public static function createMetaTag($name, $content)
	{
		$dom = new \DOMDocument();
		$element = $dom->createElement('meta');
		$element->setAttribute('name', $name);
		$element->setAttribute('content', $content);
		return $element;
	}

	/**
	 * Helper to append element to <head> in dom
	 *
	 * @param DOMDocument $dom
	 * @param DOMElement  $element
	 *
	 * @return DOMDocument
	 */
	public static function appendElementToHeadTag(\DOMDocument $dom, \DOMElement $element)
	{
		$node = $dom->importNode($element);
		$head = $dom->getElementsByTagName('head')->item(0);
		$head->appendChild($node);
		return $dom;
	}

	/**
	 * Render html procedure 
	 *
	 * Hide the implementation details and allow for 
	 * a single point to make global changes to html 
	 * from the plugin.
	 *
	 */
	public static function renderHtml($html)
	{
		echo $html;
	}
}
