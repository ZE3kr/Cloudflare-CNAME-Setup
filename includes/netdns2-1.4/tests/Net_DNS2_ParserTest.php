<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * DNS Library for handling lookups and updates.
 *
 * PHP Version 5
 *
 * Copyright (c) 2010, Mike Pultz <mike@mikepultz.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Mike Pultz nor the names of his contributors
 *     may be used to endorse or promote products derived from this
 *     software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRIC
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Networking
 * @package   Net_DNS2
 * @author    Mike Pultz <mike@mikepultz.com>
 * @copyright 2010 Mike Pultz <mike@mikepultz.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/Net_DNS2
 * @since     File available since Release 1.0.0
 *
 */

require_once '../Net/DNS2.php';


/**
 * Test class to test the parsing code
 *   
 * @category Networking
 * @package  Net_DNS2
 * @author   Mike Pultz <mike@mikepultz.com>
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link     http://pear.php.net/package/Net_DNS2
 *
 */
class Net_DNS2_ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * function to test the TSIG logic
     *
     * @return void
     * @access public
     *
     */
    public function testTSIG()
    {
        //
        // create a new packet
        //
        $request = new Net_DNS2_Packet_Request('example.com', 'SOA', 'IN');

        //
        // add a A record to the authority section, like an update request
        //
        $request->authority[] = Net_DNS2_RR::fromString('test.example.com A 10.10.10.10');
        $request->header->nscount = 1;

        //
        // add the TSIG as additional
        //
        $request->additional[] = Net_DNS2_RR::fromString('mykey TSIG Zm9vYmFy');
        $request->header->arcount = 1;

        $line = $request->additional[0]->name . '. ' . $request->additional[0]->ttl . ' ' . 
        $request->additional[0]->class . ' ' . $request->additional[0]->type . ' ' . 
        $request->additional[0]->algorithm . '. ' . $request->additional[0]->time_signed  . ' '.
        $request->additional[0]->fudge;

        //
        // get the binary packet data
        //
        $data = $request->get();

        //
        // parse the binary
        //
        $response = new Net_DNS2_Packet_Response($data, strlen($data));

        //
        // the answer data in the response, should match our initial line exactly
        //
        $this->assertSame($line, substr($response->additional[0]->__toString(), 0, 58));
    }

    /**
     * function to test parsing the individual RR's
     *
     * @return void
     * @access public
     *
     */
    public function testParser()
    {
        $rrs = array(

            'A'             => 'example.com. 300 IN A 172.168.0.50',
            'NS'            => 'example.com. 300 IN NS ns1.mrdns.com.',
            'CNAME'         => 'example.com. 300 IN CNAME www.example.com.',
            'SOA'           => 'example.com. 300 IN SOA ns1.mrdns.com. help.mrhost.ca. 1278700841 900 1800 86400 21400',
            'WKS'           => 'example.com. 300 IN WKS 128.8.1.14 6 21 25',
            'PTR'           => '1.0.0.127.in-addr.arpa. 300 IN PTR localhost.',
            'HINFO'         => 'example.com. 300 IN HINFO "PC-Intel-700mhz" "Redhat \"Linux\" 7.1"',
            'MX'            => 'example.com. 300 IN MX 10 mx1.mrhost.ca.',
            'TXT'           => 'example.com. 300 IN TXT "first record" "another records" "a third"',
            'RP'            => 'example.com. 300 IN RP louie.trantor.umd.edu. lam1.people.test.com.',
            'AFSDB'         => 'example.com. 300 IN AFSDB 3 afsdb.example.com.',
            'X25'           => 'example.com. 300 IN X25 "311 06 17 0 09 56"',
            'ISDN'          => 'example.com. 300 IN ISDN "150 862 028 003 217" "42"',
            'RT'            => 'example.com. 300 IN RT 2 relay.prime.com.',
            'NSAP'          => 'example.com. 300 IN NSAP 0x47.0005.80.005a00.0000.0001.e133.aaaaaa000151.00',
            'SIG'           => 'example.com. 300 IN SIG DNSKEY 7 1 86400 20100827211706 20100822211706 57970 gov. KoWPhMtLHp8sWYZSgsMiYJKB9P71CQmh9CnxJCs5GutKfo7Jpw+nNnDLiNnsd6U1JSkf99rYRWCyOTAPC47xkHr+2Uh7n6HDJznfdCzRa/v9uwEcbXIxCZ7KfzNJewW3EvYAxDIrW6sY/4MAsjS5XM/O9LaWzw6pf7TX5obBbLI+zRECbPNTdY+RF6Fl9K0GVaEZJNYi2PRXnATwvwca2CNRWxeMT/dF5STUram3cWjH0Pkm19Gc1jbdzlZVDbUudDauWoHcc0mfH7PV1sMpe80NqK7yQ24AzAkXSiknO13itHsCe4LECUu0/OtnhHg2swwXaVTf5hqHYpzi3bQenw==',
            'KEY'           => 'example.com. 300 IN KEY 256 3 7 AwEAAYCXh/ZABi8kiJIDXYmyUlHzC0CHeBzqcpyZAIjC7dK1wkRYVcUvIlpTOpnOVVfcC3Py9Ui/x45qKb0LytvK7WYAe3WyOOwk5klwIqRC/0p4luafbd2yhRMF7quOBVqYrLoHwv8i9LrV+r8dhB7rXv/lkTSI6mEZsg5rDfee8Yy1',
            'PX'            => 'example.com. 300 IN PX 10 ab.net2.it. o-ab.prmd-net2.admdb.c-it.',
            'AAAA'          => 'example.com. 300 IN AAAA 1080:0:0:0:8:800:200c:417a',
            'LOC'           => 'example.com. 300 IN LOC 42 21 54.675 N 71 06 18.343 W 24.12m 30.00m 40.00m 5.00m',
            'SRV'           => 'example.com. 300 IN SRV 20 0 5269 xmpp-server2.l.google.com.',
            'ATMA'          => 'example.com. 300 IN ATMA 39246f00e7c9c0312000100100001234567800',
            'NAPTR'         => 'example.com. 300 IN NAPTR 100 10 "S" "SIP+D2U" "!^.*$!sip:customer-service@example.com!" _sip._udp.example.com.',
            'KX'            => 'example.com. 300 IN KX 10 mx1.mrhost.ca.',
            'CERT'          => 'example.com. 300 IN CERT 3 0 0 TUlJQ1hnSUJBQUtCZ1FDcXlqbzNFMTU0dFU1Um43ajlKTFZsOGIwcUlCSVpGWENFelZvanVJT1BsMTM0by9zcHkxSE1hQytiUGh3Wk1UYVd4QlJpZHBFbUprNlEwNFJNTXdqdkFyLzFKWjhnWThtTzdCdTh1RUROVkNWeG5rQkUzMHhDSjhHRTNzL3EyN2VWSXBCUGFtU1lkNDVKZjNIeVBRRE4yaU45RjVHdGlIa2E2OXNhcmtKUnJ3SURBUUFCQW9HQkFJaUtDQ1NEM2FFUEFjQUx1MjdWN0JmR1BYN3lDTVg0OSsyVDVwNXNJdkduQjcrQ0NZZ09QaVQybmlpMGJPNVBBOTlnZnhPQXl1WCs5Z3llclVQbUFSc1ViUzcvUndkNGorRUlOVW1DanJSK2R6dGVXT0syeGxHamFOdGNPZU5jMkVtelQyMFRsekxVeUxTWGpzMzVlU2NQK0loeVptM2xJd21vbWtNb2d1QkFrRUE0a1FsOVBxaTJ2MVBDeGJCelU4Nnphblo2b0hsV0IzMUh4MllCNmFLYXhjNkVOZHhVejFzNjU2VncrRDhSVGpoSllyeDdMVkxzZDBRaVZJM0liSjVvUUpCQU1FN3k0aHg0SCtnQU40MEdrYjNjTFZGNHNpSEZrNnA2QVZRdlpzREwvVnh3bVlOdE4rM0txT3NVcG11WXZ3a3h0ajhIQnZtckxUYStXb3NmRDQwS1U4Q1FRQ1dvNmhob1R3cmI5bmdHQmFQQ2VDc2JCaVkrRUlvbUVsSm5mcEpuYWNxQlJ5emVid0pIeXdVOGsvalNUYXJIMk5HQzJ0bG5JMzRyS1VGeDZiTTJIWUJBa0VBbXBYSWZPNkZKL1NMM1RlWGNnQ1A5U1RraVlHd2NkdnhGeGVCcDlvRDZ2cElCN2FkWlgrMko5dzY5R0VUSlI0U3loSGVOdC95ZUhqWm9YdlhKVGc3ZHdKQVpEamxwL25wNEFZV3JYaGFrMVAvNGZlaDVNSU5WVHNXQkhTNlRZNW0xRmZMUEpybklHNW1FSHNidWkvdnhuQ1JmRUR4ZlU1V1E0cS9HUkZuaVl3SHB3PT0=',
            'DNAME'         => 'example.com. 300 IN DNAME frobozz-division.acme.example.',
            'APL'           => 'example.com. 300 IN APL 1:224.0.0.0/4 2:a0:0:0:0:0:0:0:0/8 !1:192.168.38.0/28',
            'DS'            => 'example.com. 300 IN DS 21366 7 2 96eeb2ffd9b00cd4694e78278b5efdab0a80446567b69f634da078f0d90f01ba',
            'SSHFP'         => 'example.com. 300 IN SSHFP 2 1 123456789abcdef67890123456789abcdef67890',
            'IPSECKEY'      => 'example.com. 300 IN IPSECKEY 10 2 2 2001:db8:0:8002:0:0:2000:1 AQNRU3mG7TVTO2BkR47usntb102uFJtugbo6BSGvgqt4AQ==',
            'RRSIG'         => 'example.com. 300 IN RRSIG DNSKEY 7 1 86400 20100827211706 20100822211706 57970 gov. KoWPhMtLHp8sWYZSgsMiYJKB9P71CQmh9CnxJCs5GutKfo7Jpw+nNnDLiNnsd6U1JSkf99rYRWCyOTAPC47xkHr+2Uh7n6HDJznfdCzRa/v9uwEcbXIxCZ7KfzNJewW3EvYAxDIrW6sY/4MAsjS5XM/O9LaWzw6pf7TX5obBbLI+zRECbPNTdY+RF6Fl9K0GVaEZJNYi2PRXnATwvwca2CNRWxeMT/dF5STUram3cWjH0Pkm19Gc1jbdzlZVDbUudDauWoHcc0mfH7PV1sMpe80NqK7yQ24AzAkXSiknO13itHsCe4LECUu0/OtnhHg2swwXaVTf5hqHYpzi3bQenw==',
            'NSEC'          => 'example.com. 300 IN NSEC dog.poo.com. A MX RRSIG NSEC TYPE1234',
            'DNSKEY'        => 'example.com. 300 IN DNSKEY 256 3 7 AwEAAYCXh/ZABi8kiJIDXYmyUlHzC0CHeBzqcpyZAIjC7dK1wkRYVcUvIlpTOpnOVVfcC3Py9Ui/x45qKb0LytvK7WYAe3WyOOwk5klwIqRC/0p4luafbd2yhRMF7quOBVqYrLoHwv8i9LrV+r8dhB7rXv/lkTSI6mEZsg5rDfee8Yy1',
            'DHCID'         => 'example.com. 300 IN DHCID AAIBY2/AuCccgoJbsaxcQc9TUapptP69lOjxfNuVAA2kjEA=',
            'NSEC3'         => 'example.com. 300 IN NSEC3 1 1 12 AABBCCDD b4um86eghhds6nea196smvmlo4ors995 NS DS RRSIG',
            'NSEC3PARAM'    => 'example.com. 300 IN NSEC3PARAM 1 0 1 D399EAAB',
            'TLSA'          => '_443._tcp.www.example.com. 300 IN TLSA 1 1 2 92003ba34942dc74152e2f2c408d29eca5a520e7f2e06bb944f4dca346baf63c1b177615d466f6c4b71c216a50292bd58c9ebdd2f74e38fe51ffd48c43326cbc',
            'SMIMEA'        => 'c93f1e400f26708f98cb19d936620da35eec8f72e57f9eec01c1afd6._smimecert.example.com. 300 IN SMIMEA 1 1 2 92003ba34942dc74152e2f2c408d29eca5a520e7f2e06bb944f4dca346baf63c1b177615d466f6c4b71c216a50292bd58c9ebdd2f74e38fe51ffd48c43326cbc',
            'HIP'           => 'example.com. 300 IN HIP 2 200100107B1A74DF365639CC39F1D578 AwEAAbdxyhNuSutc5EMzxTs9LBPCIkOFH8cIvM4p9+LrV4e19WzK00+CI6zBCQTdtWsuxKbWIy87UOoJTwkUs7lBu+Upr1gsNrut79ryra+bSRGQb1slImA8YVJyuIDsj7kwzG7jnERNqnWxZ48AWkskmdHaVDP4BcelrTI3rMXdXF5D rvs.example.com. another.example.com. test.domain.org.',
            'TALINK'        => 'example.com. 300 IN TALINK c1.example.com. c3.example.com.',
            'CDS'           => 'example.com. 300 IN CDS 21366 7 2 96eeb2ffd9b00cd4694e78278b5efdab0a80446567b69f634da078f0d90f01ba',
            'OPENPGPKEY'    => '8d5730bd8d76d417bf974c03f59eedb7af98cb5c3dc73ea8ebbd54b7._openpgpkey.example.com. 300 IN OPENPGPKEY AwEAAYCXh/ZABi8kiJIDXYmyUlHzC0CHeBzqcpyZAIjC7dK1wkRYVcUvIlpTOpnOVVfcC3Py9Ui/x45qKb0LytvK7WYAe3WyOOwk5klwIqRC/0p4luafbd2yhRMF7quOBVqYrLoHwv8i9LrV+r8dhB7rXv/lkTSI6mEZsg5rDfee8Yy1',
            'CSYNC'         => 'example.com. 300 IN CSYNC 1278700841 3 A NS AAAA',
            'SPF'           => 'example.com. 300 IN SPF "v=spf1 ip4:192.168.0.1/24 mx ?all"',
            'NID'           => 'example.com. 300 IN NID 10 14:4fff:ff20:ee64',
            'L32'           => 'example.com. 300 IN L32 10 10.1.2.0',
            'L64'           => 'example.com. 300 IN L64 10 2001:db8:1140:1000',
            'LP'            => 'example.com. 300 IN LP 10 l64-subnet1.example.com.',
            'EUI48'         => 'example.com. 300 IN EUI48 00-00-5e-00-53-2a',
            'EUI64'         => 'example.com. 300 IN EUI64 00-00-5e-ef-10-00-00-2a',
            'TKEY'          => 'example.com. 300 IN TKEY gss.microsoft.com. 3 123456.',
            'URI'           => 'example.com. 300 IN URI 10 1 "http://mrdns.com"',
            'CAA'           => 'example.com. 300 IN CAA 0 issue "ca.example.net; policy=ev"',
            'AVC'           => 'example.com. 300 IN AVC "first record" "another records" "a third"',
            'TA'            => 'example.com. 300 IN TA 21366 7 2 96eeb2ffd9b00cd4694e78278b5efdab0a80446567b69f634da078f0d90f01ba',
            'DLV'           => 'example.com. 300 IN DLV 21366 7 2 96eeb2ffd9b00cd4694e78278b5efdab0a80446567b69f634da078f0d90f01ba',
        );

        foreach ($rrs as $rr => $line) {

            $class_name = 'Net_DNS2_RR_' . $rr;

            //
            // create a new packet
            //
            if ($rr == 'PTR') {
                $request = new Net_DNS2_Packet_Request('1.0.0.127.in-addr.arpa', $rr, 'IN');
            } else {
                $request = new Net_DNS2_Packet_Request('example.com', $rr, 'IN');
            }

            //
            // parse the line
            //
            $a = Net_DNS2_RR::fromString($line);

            //
            // check that the object is right 
            //
            $this->assertTrue($a instanceof $class_name);
                        
            //
            // set it on the packet
            //
            $request->answer[] = $a;
            $request->header->ancount = 1;

            //
            // get the binary packet data
            //
            $data = $request->get();
                        
            //
            // parse the binary
            //
            $response = new Net_DNS2_Packet_Response($data, strlen($data));

            //
            // the answer data in the response, should match our initial line exactly
            //
            $this->assertSame($line, $response->answer[0]->__toString());
        }
    }

    /**
     * function to test the compression logic
     *
     * @return void
     * @access public
     *
     */
    public function testCompression()
    {
        //
        // this list of RR's uses name compression
        //
        $rrs = array(

            'NS'            => 'example.com. 300 IN NS ns1.mrdns.com.',
            'CNAME'         => 'example.com. 300 IN CNAME www.example.com.',
            'SOA'           => 'example.com. 300 IN SOA ns1.mrdns.com. help.mrhost.ca. 1278700841 900 1800 86400 21400',
            'MX'            => 'example.com. 300 IN MX 10 mx1.mrhost.ca.',
            'RP'            => 'example.com. 300 IN RP louie.trantor.umd.edu. lam1.people.test.com.',
            'AFSDB'         => 'example.com. 300 IN AFSDB 3 afsdb.example.com.',
            'RT'            => 'example.com. 300 IN RT 2 relay.prime.com.',
            'PX'            => 'example.com. 300 IN PX 10 ab.net2.it. o-ab.prmd-net2.admdb.c-it.',
            'SRV'           => 'example.com. 300 IN SRV 20 0 5269 xmpp-server2.l.google.com.',
            'NAPTR'         => 'example.com. 300 IN NAPTR 100 10 S SIP+D2U !^.*$!sip:customer-service@example.com! _sip._udp.example.com.',
            'DNAME'         => 'example.com. 300 IN DNAME frobozz-division.acme.example.',
            'HIP'           => 'example.com. 300 IN HIP 2 200100107B1A74DF365639CC39F1D578 AwEAAbdxyhNuSutc5EMzxTs9LBPCIkOFH8cIvM4p9+LrV4e19WzK00+CI6zBCQTdtWsuxKbWIy87UOoJTwkUs7lBu+Upr1gsNrut79ryra+bSRGQb1slImA8YVJyuIDsj7kwzG7jnERNqnWxZ48AWkskmdHaVDP4BcelrTI3rMXdXF5D rvs.example.com. another.example.com. test.domain.org.'
        );

        //
        // create a new updater object
        //
        $u = new Net_DNS2_Updater("example.com", array('nameservers' => array('10.10.0.1')));

        //
        // add each RR to the same object, so we can build a build compressed name list
        //
        foreach ($rrs as $rr => $line) {

            $class_name = 'Net_DNS2_RR_' . $rr;

            //
            // parse the line
            //
            $a = Net_DNS2_RR::fromString($line);

            //
            // check that the object is right 
            //
            $this->assertTrue($a instanceof $class_name);
                        
            //
            // set it on the packet
            //
            $u->add($a);
        }

        //
        // get the request packet
        //
        $request = $u->packet();

        //
        // get the authority section of the request
        //
        $request_authority = $request->authority;

        //
        // parse the binary
        //
        $data = $request->get();
        $response = new Net_DNS2_Packet_Response($data, strlen($data));

        //
        // get the authority section of the response, and clean up the
        // rdata so everything will match.
        //
        // the request packet doesn't have the rdlength and rdata fields
        // built yet, so it will throw off the hash
        //
        $response_authority = $response->authority;

        foreach ($response_authority as $id => $object) {

            $response_authority[$id]->rdlength = '';
            $response_authority[$id]->rdata = '';
        }

        //
        // build the hashes
        //
        $a = md5(print_r($request_authority, 1));
        $b = md5(print_r($response_authority, 1));

        //
        // the new hashes should match.
        //
        $this->assertSame($a, $b);
    }
}


?>
