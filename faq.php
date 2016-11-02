<?php
// Source: https://github.com/richi/
// Most lines of this code are (c) 2013-2016 Datenstrom, http://datenstrom.se
// This file may be used and distributed under the terms of the public license.

// FAQ plugin
class YellowFaq
{
	const Version = "0.6.10";
	var $yellow;			//access to API
	
	// Handle initialisation
	function onLoad($yellow)
	{
		$this->yellow = $yellow;
		$this->yellow->config->setDefault("faqLocation", "/faq/");
		$this->yellow->config->setDefault("faqPaginationLimit", "30");
	}
	
	// Handle page content parsing of custom block
	function onParseContentBlock($page, $name, $text, $shortcut)
	{
		$output = NULL;
		
		if($name == "faqarchive" && $shortcut)
		{
			list($location) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("faqLocation");
			$faq = $this->yellow->pages->find($location);
			$pages = $faq ? $faq->getChildren(!$faq->isVisible()) : $this->yellow->pages->clean();
			$page->setLastModified($pages->getModified());
			$months = array();
			foreach($pages as $page) if(preg_match("/^(\d+\-\d+)\-/", $page->get("modified"), $matches)) ++$months[$matches[1]];
			if(count($months))
			{
				uksort($months, strnatcasecmp);
				$months = array_reverse($months);
				$output = "<div class=\"".htmlspecialchars($name)."\">\n";
				$output .= "<ul>\n";
				foreach($months as $key=>$value)
				{
					$output .= "<li><a href=\"".$faq->getLocation().$this->yellow->toolbox->normaliseArgs("modified:$key")."\">";
					$output .= htmlspecialchars($this->yellow->text->normaliseDate($key))."</a></li>\n";
				}
				$output .= "</ul>\n";
				$output .= "</div>\n";
			} else {
				$output = "<div class=\"".htmlspecialchars($name)."\"><ul><li> - </li></ul></div>\n";
			}
			
		}
		if($name == "faqrecent" && $shortcut)
		{
			list($location, $pagesMax) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("faqLocation");
			if(empty($pagesMax)) $pagesMax = 10;			
			$faq = $this->yellow->pages->find($location);
			$pages = $faq ? $faq->getChildren(!$faq->isVisible()) : $this->yellow->pages->clean();
			$pages->sort("modified", false)->limit($pagesMax);
			$page->setLastModified($pages->getModified());
			if(count($pages))
			{
				$output = "<div class=\"".htmlspecialchars($name)."\">\n";
				$output .= "<ul>\n";
				foreach($pages as $page)
				{
					$output .= "<li><a href=\"".$page->getLocation()."\">".$page->getHtml("titleNavigation")."</a></li>\n";
				}
				$output .= "</ul>\n";
				$output .= "</div>\n";
			} else {
				$output = "<div class=\"".htmlspecialchars($name)."\"><ul><li> - </li></ul></div>\n";
			}
		}
		if($name == "faqrelated" && $shortcut)
		{
			list($location, $pagesMax) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("faqLocation");
			if(empty($pagesMax)) $pagesMax = 4;
			$faq = $this->yellow->pages->find($location);
			$pages = $faq ? $faq->getChildren(!$faq->isVisible()) : $this->yellow->pages->clean();
			$pages->similar($page->getPage("main"))->limit($pagesMax);
			$page->setLastModified($pages->getModified());
			if(count($pages))
			{
				$output = "<div class=\"".htmlspecialchars($name)."\">\n";
				$output .= "<ul>\n";
				foreach($pages as $page)
				{
					$output .= "<li><a href=\"".$page->getLocation()."\">".$page->getHtml("titleNavigation")."</a></li>\n";
				}
				$output .= "</ul>\n";
				$output .= "</div>\n";
			} else {
				$output = "<div class=\"".htmlspecialchars($name)."\"><ul><li> - </li></ul></div>\n";
			}
		}
		if($name == "faqtags" && $shortcut)
		{
			list($location) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("faqLocation");
			$faq = $this->yellow->pages->find($location);
			$pages = $faq ? $faq->getChildren(!$faq->isVisible()) : $this->yellow->pages->clean();
			$page->setLastModified($pages->getModified());
			$tags = array();
			foreach($pages as $page) if($page->isExisting("tag")) foreach(preg_split("/,\s*/", $page->get("tag")) as $tag) ++$tags[$tag];
			if(count($tags))
			{
				uksort($tags, strnatcasecmp);
				$output = "<div class=\"".htmlspecialchars($name)."\">\n";
				$output .= "<ul>\n";
				foreach($tags as $key=>$value)
				{
					$output .= "<li><a href=\"".$faq->getLocation().$this->yellow->toolbox->normaliseArgs("tag:$key")."\">";
					$output .= htmlspecialchars($key)."</a></li>\n";
				}
				$output .= "</ul>\n";
				$output .= "</div>\n";
			} else {
				$output = "<div class=\"".htmlspecialchars($name)."\"><ul><li> - </li></ul></div>\n";
			}
		}
		return $output;
	}
	
	// Handle page parsing
	function onParsePage()
	{
		if($this->yellow->page->get("template") == "faqpages")
		{
			$chronologicalOrder = false;
			$pages = $this->yellow->page->getChildren(!$this->yellow->page->isVisible());
			$pagesFilter = array();
			
			if($_REQUEST["tag"])
			{
				$pages->filter("tag", $_REQUEST["tag"]);
				array_push($pagesFilter, $pages->getFilter());
			}
			if($_REQUEST["title"])
			{
				$pages->filter("title", $_REQUEST["title"], false);
				array_push($pagesFilter, $pages->getFilter());
			}
			if($_REQUEST["modified"])
			{
				$chronologicalOrder = true;
				$pages->filter("modified", $_REQUEST["modified"], false);
				array_push($pagesFilter, $this->yellow->text->normaliseDate($pages->getFilter()));
			}
			if($_REQUEST["list"] == "modified")
			{
				$chronologicalOrder = true;
				array_push($pagesFilter, $this->yellow->text->get("faqListModified"));
			}
			if(empty($pagesFilter) || $_REQUEST["list"] == "title")
			{
				array_push($pagesFilter, $this->yellow->text->get("faqListTitle"));
			}
			
			$pages->sort($chronologicalOrder ? "modified" : "title", $chronologicalOrder);
			$pages->pagination($this->yellow->config->get("faqPaginationLimit"));
			if(!$pages->getPaginationNumber()) $this->yellow->page->error(404);
			
			$title = implode(' ', $pagesFilter);
			$this->yellow->page->set("faqpagesChronologicalOrder", $chronologicalOrder);
			$this->yellow->page->set("titleHeader", $this->yellow->text->get("faqFilter")." - ".$title);
			$this->yellow->page->set("titleFaq", $this->yellow->text->get("faqFilter")." ".$title);
			$this->yellow->page->setPages($pages);
			$this->yellow->page->setLastModified($pages->getModified());
			$this->yellow->page->setHeader("Cache-Control", "max-age=60");
		}
	}
	
	// Handle page extra HTML data
	function onExtra($name)
	{
		return $this->onParseContentBlock($this->yellow->page, $name, "", true);
	}
}

$yellow->plugins->register("faq", "YellowFaq", YellowFaq::Version);
?>