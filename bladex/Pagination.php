<?php

namespace Bladex;


use Bitrix\Main\Web;
class Pagination
{
    protected $id;
    protected $pageSizes = array();
    protected $pageSize = 20;
    protected $recordCount;
    protected $currentPage;
    protected $allowAll = false;
    protected $allRecords = false;

    /**
     * @param string $id Navigation identity like "nav-cars".
     */
    public function __construct(string $id = 'nav')
    {
        $this->id = $id;
    }

    /**
     * Initializes the navigation from URI.
     *
     * @return void
     */
    public function initFromUri()
    {
        $navParams = [];

        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        if (($value = $request->getQuery($this->id)) !== null) {
            //parameters are in the QUERY_STRING
            $params = explode("-", $value);
            for ($i = 0, $n = count($params); $i < $n; $i += 2) {
                $navParams[$params[$i]] = $params[$i + 1];
            }
        } else {
            //probably parametrs are in the SEF URI
            $matches = [];
            if (preg_match("'/" . preg_quote($this->id, "'") . "/page-([\\d]+|all)+(/size-([\\d]+))?'", $request->getRequestUri(), $matches)) {
                $navParams["page"] = $matches[1];
                if (isset($matches[3])) {
                    $navParams["size"] = $matches[3];
                }
            }
        }

        if (isset($navParams["size"])) {
            //set page size from user request
            if (in_array($navParams["size"], $this->pageSizes)) {
                $this->setPageSize((int) $navParams["size"]);
            }
        }

        if (isset($navParams["page"])) {
            if ($navParams["page"] == "all" && $this->allowAll == true) {
                //show all records in one page
                $this->allRecords = true;
            } else {
                //set current page within boundaries
                $currentPage = (int) $navParams["page"];
                $this->setCurrentPage($currentPage);
            }
        }
    }

    /**
     * Returns number of pages or 1 if recordCount is not set.
     * @return int
     */
    public function getPageCount()
    {
        if ($this->allRecords) {
            return 1;
        }

        if ($this->recordCount === null || $this->recordCount <= 0) {
            return 1;
        }

        $maxPages = (int) ceil($this->recordCount / $this->pageSize);
        return max(1, $maxPages);
    }

    /**
     * @param int $n Page size.
     * @return $this
     */
    public function setPageSize($n)
    {
        $this->pageSize = max(1, (int) $n);
        return $this;
    }

    /**
     * @param int $n The current page number.
     * @return $this
     */
    public function setCurrentPage($n)
    {
        $page = (int) max(1, (int) $n);

        // Если количество записей установлено, проверяем максимальную страницу
        if ($this->recordCount !== null) {
            $maxPage = $this->getPageCount();
            $page = (int) min($page, $maxPage);
        }

        $this->currentPage = $page;

        return $this;
    }

    /**
     * Returns the current page number.
     * @return int
     */
    public function getCurrentPage()
    {

        if ($this->currentPage !== null) {
            $maxPage = $this->getPageCount();
            return $this->currentPage > $maxPage ? $maxPage : $this->currentPage;
        }

        return 1;
    }

    /**
     * @param bool $mode Allows to show all records, yes or no.
     * @return $this
     */
    public function allowAllRecords($mode)
    {
        $this->allowAll = (bool) $mode;
        return $this;
    }

    /**
     * @param int $n Number of records (to calculate number of pages).
     * @return $this
     */
    public function setRecordCount($n)
    {
        $this->recordCount = $n;

        return $this;
    }

    /**
     * Returns number of records.
     * @return int
     */
    public function getRecordCount()
    {
        return $this->recordCount ?? 0;
    }

    /**
     * This controls which sizes are available via user interface.
     * @param array $sizes Array of integers.
     * @return $this
     */
    public function setPageSizes(array $sizes)
    {
        $this->pageSizes = array_filter($sizes, function ($size) {
            return is_numeric($size) && $size > 0;
        });
        return $this;
    }

    /**
     * Returns allowed page sizes.
     * @return array
     */
    public function getPageSizes()
    {
        return $this->pageSizes;
    }

    /**
     * Returns "formal" page size.
     * @return int
     */
    public function getPageSize()
    {
        return max(1, $this->pageSize);
    }

    /**
     * Returns navigation ID.
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns offset of the first record of the current page.
     * @return int
     */
    public function getOffset()
    {
        if ($this->allRecords) {
            return 0;
        }
        return max(0, ($this->getCurrentPage() - 1) * $this->getPageSize());
    }

    /**
     * Returns the number of records in the current page.
     * @return int
     */
    public function getLimit()
    {
        if ($this->allRecords) {
            return $this->getRecordCount();
        }
        return $this->getPageSize();
    }

    /**
     * Returns true if all the records are shown in one page.
     * @return bool
     */
    public function allRecordsShown()
    {
        return $this->allRecords;
    }

    /**
     * Returns true if showing all records is allowed.
     * @return bool
     */
    public function allRecordsAllowed()
    {
        return $this->allowAll;
    }

    /**
     * Generates navigation data structure for templates
     * 
     * @param int $range Number of page links to show around current page
     * @param bool $sef SEF mode for URL generation
     * @param Web\Uri|null $baseUri Base URI for link generation
     * @return array Navigation data structure
     */
    public function getNavigationData($range = 5, $sef = false, Web\Uri $baseUri = null)
    {
        if ($baseUri === null) {
            $request = \Bitrix\Main\Context::getCurrent()->getRequest();
            $baseUri = new Web\Uri($request->getRequestUri());
        }

        $currentPage = $this->getCurrentPage();

        $pageCount = $this->getPageCount();
        $range = max(1, (int) $range);

        $data = [
            'current_page' => $currentPage,
            'page_count' => $pageCount,
            'record_count' => $this->getRecordCount(),
            'page_size' => $this->getPageSize(),
            'has_pages' => $pageCount > 1,
            'pages' => [],
            'links' => [
                'first' => null,
                'prev' => null,
                'next' => null,
                'last' => null,
                'all' => null
            ]
        ];

        // Если только одна страница, возвращаем базовую структуру
        if ($pageCount <= 1) {
            return $data;
        }

        // Вычисляем диапазон страниц для отображения
        $startPage = (int) max(1, $currentPage - floor($range / 2));
        $endPage = (int) min($pageCount, $startPage + $range - 1);

        // Корректируем начальную страницу, если конечная достигла максимума
        $startPage = (int) max(1, $endPage - $range + 1);

        // Генерируем массив страниц
        for ($i = $startPage; $i <= $endPage; $i++) {

            $data['pages'][] = [
                'number' => $i,
                'url' => $this->addParams(clone $baseUri, $sef, $i)->getUri(),
                'is_current' => $i === $currentPage
            ];
        }

        // Генерируем ссылки навигации
        if ($currentPage > 1) {
            $data['links']['first'] = $this->addParams(clone $baseUri, $sef, 1)->getUri();
            $data['links']['prev'] = $this->addParams(clone $baseUri, $sef, $currentPage - 1)->getUri();
        }

        if ($currentPage < $pageCount) {
            $data['links']['next'] = $this->addParams(clone $baseUri, $sef, $currentPage + 1)->getUri();
            $data['links']['last'] = $this->addParams(clone $baseUri, $sef, $pageCount)->getUri();
        }

        // Ссылка "показать все" если разрешено
        if ($this->allRecordsAllowed()) {
            $data['links']['all'] = $this->addParams(clone $baseUri, $sef, 'all')->getUri();
        }

        // Добавляем информацию о необходимости многоточий
        $data['show_first_ellipsis'] = $startPage > 1;
        $data['show_last_ellipsis'] = $endPage < $pageCount;

        // Добавляем информацию об отображаемых записях
        $recordsFrom = $this->getOffset() + 1;
        $recordsTo = min($this->getOffset() + $this->getPageSize(), $this->getRecordCount());

        $data['records_info'] = [
            'from' => $recordsFrom,
            'to' => $recordsTo,
            'total' => $this->getRecordCount()
        ];

        return $data;
    }

    /**
     * Returns an URI with navigation parameters compatible with initFromUri().
     * @param Web\Uri $uri
     * @param bool $sef SEF mode.
     * @param string|int $page Page number or 'all'.
     * @param string|int|null $size Page size.
     * @return Web\Uri
     */
    public function addParams(Web\Uri $uri, $sef, $page, $size = null)
    {
        if ($sef == true) {
            $this->clearParams($uri, $sef);

            $path = $uri->getPath();
            $pos = mb_strrpos($path, "/");
            $path = mb_substr($path, 0, $pos + 1) . $this->id . "/page-" . $page . "/" . ($size !== null ? "size-" . $size . "/" : '') . mb_substr($path, $pos + 1);
            $uri->setPath($path);
        } else {
            $uri->addParams(array($this->id => "page-" . $page . ($size !== null ? "-size-" . $size : '')));
        }
        return $uri;
    }

    /**
     * Clears an URI from navigation parameters and returns it.
     * @param Web\Uri $uri
     * @param bool $sef SEF mode.
     * @return Web\Uri
     */
    public function clearParams(Web\Uri $uri, $sef)
    {
        if ($sef == true) {
            $path = $uri->getPath();
            $path = preg_replace("'/" . preg_quote($this->id, "'") . "/page-([\\d]+|all)+(/size-([\\d]+))?'", "", $path);
            $uri->setPath($path);
        } else {
            $uri->deleteParams(array($this->id));
        }
        return $uri;
    }
}