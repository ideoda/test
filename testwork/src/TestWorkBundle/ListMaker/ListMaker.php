<?php

namespace App\TestWorkBundle\ListMaker;

use App\TestWorkBundle\Connector\IcndbConnector;
use App\TestWorkBundle\Connector\TwitterConnector;
use App\TestWorkBundle\Descriptor\ListMakerDescriptor;
use App\TestWorkBundle\Exception\RequestValidationException;

/**
 * Class ListMaker
 * @package App\TestWorkBundle\ListMaker
 */
class ListMaker
{
    /**
     * @var TwitterConnector
     */
    protected $twitterConnector;

    /**
     * @var IcndbConnector
     */
    protected $icndbConnector;

    /**
     * ListMaker constructor.
     * @param TwitterConnector $twitterConnector
     * @param IcndbConnector   $icndbConnector
     */
    public function __construct(TwitterConnector $twitterConnector, IcndbConnector $icndbConnector)
    {
        $this->twitterConnector = $twitterConnector;
        $this->icndbConnector   = $icndbConnector;
    }

    /**
     * @param ListMakerDescriptor $descriptor
     * @return string
     */
    public function makeList(ListMakerDescriptor $descriptor): string
    {
        /**
         * nem volt feladat, hogy a "limit" paraméterezhető legyen
         */

        $tweetList1 = $this->twitterConnector->getLastTweets($descriptor->getHandle1(), 20);
        $tweetList2 = $this->twitterConnector->getLastTweets($descriptor->getHandle2(), 20);

        if (isset($tweetList1['errors'])) {
            throw new RequestValidationException(sprintf('twitter.user.%s.not.exist', $descriptor->getHandle1()));
        }
        if (isset($tweetList2['errors'])) {
            throw new RequestValidationException(sprintf('twitter.user.%s.not.exist', $descriptor->getHandle2()));
        }

        $list = array_merge($tweetList1, $tweetList2);
        $this->orderList($list);
        $this->extendList($list, $descriptor->getMethod());

        return $this->transformListToHtml($list);
    }

    /**
     * @param array $list
     * @return array
     */
    protected function orderList(array &$list): array
    {
        usort($list, function ($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });

        return $list;
    }

    /**
     * @param array  $list
     * @param string $method
     * @return array
     */
    protected function extendList(array &$list, string $method): void
    {
        if ($method === 'mod') {
            $list = $this->extendListMod($list);
        } elseif ($method === 'fib') {
            $list = $this->extendListFib($list);
        }
    }

    /**
     * @param array $list
     * @return array
     */
    protected function extendListMod(array $list): array
    {
        $extendedList = [];
        $counter      = 0;

        foreach ($list as $elem) {
            if ($counter === 2) {
                $extendedList[] = $this->icndbConnector->getRandom();
                $extendedList[] = $elem;
                $counter--;
            } else {
                $extendedList[] = $elem;
                $counter++;
            }
        }

        return $extendedList;
    }

    /**
     * @param array $list
     * @return array
     */
    protected function extendListFib(array $list): array
    {
        $extendedList = [];

        for ($counter = 0; $counter < \count($list); $counter++) {

            $extendedListCount          = \count($extendedList);
            $currentPlaceOnExtendedList = $extendedListCount + 1;

            if ($currentPlaceOnExtendedList > 2 && $this->isFibonacci($currentPlaceOnExtendedList)) {
                $extendedList[] = $this->icndbConnector->getRandom();
                $counter--;
            } else {
                $extendedList[] = $list[$counter];
            }
        }

        return $extendedList;
    }

    /**
     * @param int $inspectedNumber
     * @return bool
     */
    protected function isFibonacci(int $inspectedNumber): bool
    {
        $count = 0;

        while (true) {

            $actualFibonacciNumber = $this->getNthFibonacci($count);

            if ($inspectedNumber === $actualFibonacciNumber) {
                return true;
            }

            if ($actualFibonacciNumber > $inspectedNumber) {
                return false;
            }

            $count++;
        }
    }

    /**
     * @param int $number
     * @return int
     */
    protected function getNthFibonacci(int $number): int
    {
        if ($number === 0) {
            return 0;
        }

        if ($number === 1) {
            return 1;
        }

        return ($this->getNthFibonacci($number - 1) +
                $this->getNthFibonacci($number - 2));
    }

    /**
     * @param array               $list
     * @return string
     */
    protected function transformListToHtml(array $list): string
    {
        $htmlString = '<html lang="hu"><body><table><thead><tr><th>number</th><th>source</th><th>time</th><th>message</th></tr></thead><tbody>';
        $lineNumber = 1;

        foreach ($list as $elem) {

            $htmlString .= '<tr>';

            if (isset($elem['id'])) {
                $htmlString .=
                    '<td>'.$lineNumber.'.</td>'.
                    '<td>'.'twitter/'.$elem['user']['screen_name'].'</td>'.
                    '<td>'.date('Y.M.D h:i', strtotime($elem['created_at'])).'</td>'.
                    '<td>'.$elem['text'].'</td>';
            } else {
                $htmlString .=
                    '<td>'.$lineNumber.'.</td>'.
                    '<td>icndb</td>'.
                    '<td></td>'.
                    '<td>'.$elem['value']['joke'].'</td>';
            }

            $htmlString .= '</tr>';

            $lineNumber++;
        }

        $htmlString .= '</tbody></table></body></html>';

        return $htmlString;
    }

}