# fm2json (Filemaker to JSON)

FM2JSON is a **PHP script** that outputs a JSON string of the query results from the XML results obtained from a Filemaker Pro Server (Advanced) v12+ [Custom Web Publishing](http://www.google.com/url?sa=t&rct=j&q=cwp%20xml%20filemaker&source=web&cd=2&cad=rja&ved=0CDMQFjAB&url=http%3A%2F%2Fwww.filemaker.com%2Fsupport%2Fproduct%2Fdocs%2F12%2Ffms%2Ffms12_cwp_xml_en.pdf&ei=3HVJUN-cHc-VmQWm1YHYDQ&usg=AFQjCNFqBQ2NM8mA_XOzOaIuYUi7PbyAxQ) (CWP). 

Background: Versions of FMP Server allowed for XSLT processing via the Web Publishing Engine in FileMaker Server Advanced, but Filemaker has removed that function as of v.12. I have a few applications that were using [Six Fried Rice's *fm2json* XSLT Filemaker to JSON Converter](http://sixfriedrice.com/wp/products/filemaker-to-json-converter/), but didn't want to rewrite my apps to use Filemaker's XML results.

## Configuration
You just need to edit the HOST_PORT variable definition to where your Filemaker Server is hosting CWP.

## Documentation
Follow the parameters used for XML querying in the [Filemaker Pro 12 Custom Web Publishing Manual](http://www.google.com/url?sa=t&rct=j&q=cwp%20xml%20filemaker&source=web&cd=2&cad=rja&ved=0CDMQFjAB&url=http%3A%2F%2Fwww.filemaker.com%2Fsupport%2Fproduct%2Fdocs%2F12%2Ffms%2Ffms12_cwp_xml_en.pdf&ei=3HVJUN-cHc-VmQWm1YHYDQ&usg=AFQjCNFqBQ2NM8mA_XOzOaIuYUi7PbyAxQ)

## The JSON Response ##
The JSON object returned has the basic structure as below. Note that this is using Filemaker XML Grammar *fmresultset*. See [Six Fried Rice's fm2json](http://sixfriedrice.com/wp/products/filemaker-to-json-converter/) for more information about the JSON object generated. It's generally the same, with the exception of the **error code**.

<pre>
{
  "error": {
    "code": "0"
  },
  "product": {
    "build": "03/15/2012",
    "name": "FileMaker Web Publishing Engine",
    "version": "12.0.1.150"
  },
  "datasource": {
    "database": "myDatabase",
    "date-format": "MM/dd/yyyy",
    "layout": "myLayout",
    "table": "myTable",
    "time-format": "HH:mm:ss",
    "timestamp-format": "MM/dd/yyyy HH:mm:ss",
    "total-count": "100"
  },
  "metadata": [
    
  ],
  "resultset": {
    "count": "1",
    "fetch-size": "1",
    "records": [
      {
        "mod-id": "235",
        "record-id": "32",
        "fields": {
          "myField1": [
            "myData1"
          ],
          "myField2": [
            "myData2"
          ],
          "myField3": [
            "repetition1",
            "repetition2",
            "repetition3",
            "repetition4",
            ""
          ],
          "myFieldWithEmptyRepetitions": [
            "",
            "",
            "",
            "",
            ""
          ]
        }
      }
    ]
  }
}
</pre>

## Copyright
###The MIT License (MIT)

Copyright (c) 2012 Ian Shen (2b3 Productions)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.