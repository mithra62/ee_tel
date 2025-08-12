<?php

namespace Mithra62\Tel\Tags;

use ExpressionEngine\Service\Addon\Controllers\Tag\AbstractRoute;

abstract class AbstractTag extends AbstractRoute
{
    /**
     * Due to how EE handles SQL IN() calls atm(?), we have to get creative to paginate certain content.
     * This method takes an array and returns those elements within the `$limit` and `$page` values
     * @param array $data
     * @param int $limit
     * @param int $page
     * @return array
     */
    protected function paginateArray(array $data, int $limit, int $page): array
    {
        $data = array_merge($data); //reset the keys
        $start = $limit * ($page - 1);
        $end = ($start + $limit) - 1;
        $return = [];
        for ($i = $start; $i <= $end; $i++) {
            if (!empty($data[$i]) && is_array($data[$i])) {
                $data[$i]['next_row'] = 'n';
                if (isset($data[($i + 1)])) {
                    $data[$i]['next_row'] = 'y';
                }

                $return[] = $data[$i];
            }
        }

        return $return;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasParam(string $key): bool
    {
        return isset(ee()->TMPL->tagparams[$key]);
    }

    /**
     * Parse template with provided data
     *
     * @param array $tag_vars
     * @param string $tag_data
     * @return string
     */
    public function parseVariables(array $tag_vars, $tag_data = '')
    {
        $tag_data = empty($tag_data) ? $this->tagdata() : $tag_data;

        $total = count($tag_vars);
        $count = 1;
        foreach ($tag_vars as $key => $value) {
            $tag_vars[$key]['first_row'] = $count === 1 ? 'y' : 'n';
            $tag_vars[$key]['last_row'] = $total === $count ? 'y' : 'n';
            $tag_vars[$key]['absolute_results'] = $total;
            $tag_vars[$key]['counter'] = $count;
            $count++;
        }

        return ee()->TMPL->parse_variables($tag_data, $tag_vars);
    }

    /**
     * @return string
     */
    public function tagdata(): string
    {
        return ee()->TMPL->tagdata;
    }

    /**
     * Set tag data
     *
     * @param string $tagdata
     * @return AbstractTag
     */
    public function setTagdata(string $tagdata): AbstractTag
    {
        ee()->TMPL->tagdata = $tagdata;
        return $this;
    }

    /**
     * @return array
     */
    public function getVarSingle()
    {
        return ee()->TMPL->var_single;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setVarSingle($data)
    {
        ee()->TMPL->var_single = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getVarPair()
    {
        return ee()->TMPL->var_pair;
    }

    /**
     * @param $data
     * @return AbstractTag
     */
    public function setVarPair($data): AbstractTag
    {
        ee()->TMPL->var_pair = $data;
        return $this;
    }

    /**
     * Get all tag parameters
     *
     * @return array
     */
    public function params()
    {
        return ee()->TMPL->tagparams;
    }

    /**
     * @param string $key
     * @param bool $default
     * @param bool $castBooleans
     * @return bool|mixed|string
     */
    public function param(string $key, $default = false, $castBooleans = true)
    {
        if (!isset(ee()->TMPL->tagparams[$key]) || ee()->TMPL->tagparams[$key] == '') {
            return $default;
        }

        if (is_bool(ee()->TMPL->tagparams[$key])) {
            return ee()->TMPL->tagparams[$key];
        }

        switch (strtolower(ee()->TMPL->tagparams[$key])) {
            case 'true':
            case 't':
            case 'yes':
            case 'y':
            case 'on':
                return $castBooleans ? true : 'yes';

            case 'false':
            case 'f':
            case 'no':
            case 'n':
            case 'off':
                return $castBooleans ? false : 'no';

            default:
                // Remove leading and trailing whitespace
                return trim(str_replace(['&nbsp;', '&#32;'], [' ', ' '], ee()->TMPL->tagparams[$key]));
        }
    }

    /**
     * @param string $key
     * @param array $default
     * @param string $delimiter
     * @return array
     */
    public function explodeParam(string $key, array $default = [], string $delimiter = '|'): array
    {
        if (!$this->hasParam($key) || $this->param($key) == '') {
            return $default;
        }

        return explode($delimiter, $this->param($key, $default));
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setParam(string $key, string $value): AbstractTag
    {
        ee()->TMPL->tagparams[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return void
     */
    public function clearParam(string $key): void
    {
        unset(ee()->TMPL->tagparams[$key]);
    }

    /**
     * @param string $tagName
     * @return string
     */
    public function noResults(string $tagName)
    {
        if (!empty($tagName) && strpos(ee()->TMPL->tagdata, 'if ' . $tagName) !== false &&
            preg_match('/' . LD . 'if ' . $tagName . RD . '(.*?)' . LD . '\/if' . RD . '/s', ee()->TMPL->tagdata, $match)
        ) {
            // currently this won't handle nested conditional statements.. lame
            return $match[1];
        }

        return ee()->TMPL->no_results();
    }

    /**
     * Parse template rows with provided data
     *
     * @param array $row
     * @return string
     */
    public function parseVariablesRow(array $row)
    {
        if ($prefix = $this->param('variable_prefix')) {
            $row = array_merge($row, array_key_prefix($row, $prefix));
        }

        return ee()->TMPL->parse_variables_row($this->tagdata(), $row);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setFlashdata(array $data)
    {
        ee()->session->set_flashdata($data);
        return $this;
    }

    /**
     * @return string|false
     */
    public function getMemberId(): int|bool
    {
        return ee()->session->userdata('member_id');
    }

    /**
     * @return string|false
     */
    public function getGroupId(): string|bool
    {
        return ee()->session->userdata('role_id');
    }

    /**
     * @return array|false
     */
    public function getRoles(): array|bool
    {
        return ee()->session->getMember()->getAllRoles()->pluck('role_id');
    }

    /**
     * @return bool
     */
    public function memberLoggedIn(): bool
    {
        return $this->getMemberId() !== 0;
    }

    /**
     * @return void
     */
    protected function guardLoggedOutRedirect(): void
    {
        if (!$this->memberLoggedIn() && $this->hasParam('logged_out_redirect')) {
            ee()->template_helper->tag_redirect($this->param('logged_out_redirect'));
        }
    }

    /**
     * @param string $market
     * @return array
     */
    public function getMarket(string $market): array
    {
        return ee('ah_utils:MarketsService')->getMarket($market);
    }

    /**
     * @return bool
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
}